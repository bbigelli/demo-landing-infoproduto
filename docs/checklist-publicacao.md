# Checklist de montagem e publicação — Demo 1

Tempo estimado para executar tudo: **2h a 3h**, sendo a maior parte no
passo 10 (performance).

Marque cada item. O que estiver com **[você]** depende de ação manual sua;
o que estiver com **[pronto]** já está no repositório.

---

## 1. Instalar o LocalWP — [você] ~15 min

- [ ] Baixar em https://localwp.com (gratuito, sem cadastro obrigatório)
- [ ] Instalar e abrir
- [ ] `Create a new site`
  - Nome do site: `demo-treino-casa`
  - Environment: **Preferred** (PHP 8.2+, MySQL 8, nginx)
  - Usuário e senha do WordPress: anote, você vai usar várias vezes
- [ ] Clicar em `WP Admin` e confirmar que o painel abre

> O LocalWP já traz PHP e MySQL embutidos. Não precisa instalar nada mais
> na máquina — por isso a escolha por ele em vez de XAMPP.

---

## 2. Tema e plugin base — [você] ~10 min

No painel do WordPress:

- [ ] **Aparência → Temas → Adicionar novo** → buscar `Hello Elementor` →
      Instalar e **Ativar**
- [ ] **Plugins → Adicionar novo** → buscar `Elementor` → Instalar e Ativar
- [ ] Pular o wizard de onboarding do Elementor (`Skip`)

Não instale Elementor Pro. A página foi construída inteira com widgets da
versão gratuita, de propósito: demo que depende de plugin pago é demo que
você não consegue entregar para cliente que não quer pagar licença.

---

## 3. Instalar o child theme — [você] ~5 min

- [ ] No LocalWP, clicar em `Go to site folder`
- [ ] Navegar até `app/public/wp-content/themes/`
- [ ] Copiar a pasta `child-theme/` deste repositório para lá, **renomeando
      para** `treino-casa-30`
- [ ] No WordPress: **Aparência → Temas** → ativar **Treino em Casa 30 Dias**

Se o tema não aparecer, confira que o cabeçalho de `style.css` continua
intacto — o WordPress identifica o tema por aqueles comentários, e
`Template: hello-elementor` é o que o liga ao tema pai.

---

## 4. Criar a página — [você] ~3 min

- [ ] **Páginas → Adicionar nova**
- [ ] Título: `Treino em Casa 30 Dias`
- [ ] Permalink (slug): `treino-em-casa-30-dias`
- [ ] Publicar
- [ ] **Configurações → Leitura → Sua página inicial exibe: Uma página
      estática** → escolher a página criada

O passo da página inicial não é cosmético: `functions.php` usa
`tc_is_landing()` para decidir onde injetar o Pixel, o WhatsApp e a barra
de oferta. Fora da home ou do slug acima, nada é injetado.

---

## 5. Importar o template do Elementor — [você] ~5 min

- [ ] Abrir a página criada e clicar em **Editar com Elementor**
- [ ] No painel, clicar no ícone de pasta (**Adicionar template**)
- [ ] Aba **Meus templates** → botão **Importar template**
- [ ] Enviar `elementor/landing-treino-30-dias.json`
- [ ] Clicar em **Inserir**
- [ ] **Atualizar**

A página inteira monta de uma vez: 10 seções, 127 elementos. Tudo é
editável normalmente no Elementor — o template carrega estrutura e texto,
e o visual vem do CSS do child theme.

### Se as seções aparecerem sem estilo

É sinal de que o child theme não está ativo ou de que o cache do Elementor
está servindo CSS antigo. **Elementor → Ferramentas → Regenerar CSS e
dados** resolve.

---

## 6. Fontes locais — [você] ~10 min

O tema espera dois arquivos que não estão no repositório, porque fonte é
binário e não deve viver em repositório de código.

- [ ] Baixar a Inter em https://fonts.google.com/specimen/Inter
      (ou usar https://gwfh.mranftl.com/fonts/inter para já obter woff2)
- [ ] Pegar apenas os pesos **400 (Regular)** e **700 (Bold)**, subset
      **latin**
- [ ] Criar a pasta `wp-content/themes/treino-casa-30/fonts/`
- [ ] Renomear e colocar os arquivos como:
      - `fonts/inter-400.woff2`
      - `fonts/inter-700.woff2`

O subset `latin` cobre toda a acentuação do português — os caracteres de
`á` a `ú` ficam na faixa `U+00C0–U+00FF`, já declarada no `unicode-range`
do `style.css`.

Se os arquivos não existirem, a página não quebra: `tc_resource_hints()`
checa com `file_exists()` antes de anunciar o preload, e o `font-family`
cai para a fonte do sistema. Só fica visualmente diferente do previsto.

---

## 7. Imagens — [você] ~20 min

O template aponta para o placeholder do Elementor, então a página importa
sem imagem quebrada. Para a versão final:

- [ ] Hero: 960×720 (4:3), pessoa treinando em sala de casa, luz natural
- [ ] Instrutor: 600×800 (3:4), retrato
- [ ] Converter as duas para **WebP** com qualidade 80
      (https://squoosh.app resolve, sem instalar nada)
- [ ] **Mídia → Adicionar nova** e enviar
- [ ] No Elementor, clicar em cada imagem e trocar pela da biblioteca
- [ ] Preencher o **texto alternativo** de cada uma — os textos estão em
      `docs/copy.md`, seção "Microcopy"
- [ ] Copiar a URL da imagem do hero e colar em `functions.php`, chave
      `hero_image` da função `tc_config()`

Banco de imagens gratuito e sem atribuição: Pexels ou Unsplash. Evite foto
de academia e de corpo de atleta — a copy vende "cabe na sua rotina", e
foto de atleta contradiz a promessa.

**O `hero_image` importa.** Sem ele o preload não acontece, e o LCP no
celular piora de forma mensurável — é o item que mais pesa na nota.

---

## 8. Número do WhatsApp — [você] ~2 min

- [ ] Em `functions.php`, função `tc_config()`, trocar `whatsapp_phone`
      pelo número real no formato `55` + DDD + número, só dígitos
- [ ] Em `build/content.json`, trocar o mesmo campo e rodar
      `node build/generate-elementor.mjs` de novo
- [ ] Reimportar o template, ou editar o link do botão direto no Elementor

Os dois lugares existem porque o botão flutuante é PHP e o botão da seção
de fechamento é widget do Elementor. Trocar só um deixa a página com dois
números diferentes — já vi isso acontecer em site no ar.

---

## 9. Pixel da Meta — [você] ~10 min

**Onde o Pixel é configurado:** três pontos, todos em `functions.php`.

### 9.1 O ID (nunca no repositório)

- [ ] No LocalWP, abrir `app/public/wp-config.php`
- [ ] Antes da linha `/* That's all, stop editing! */`, acrescentar:

```php
define( 'TC_META_PIXEL_ID', '000000000000000' );
```

- [ ] Trocar pelo ID real, obtido em
      **Gerenciador de Eventos → Fontes de dados → seu Pixel**

Sem a constante, **nenhum script de rastreamento é impresso**. É o
comportamento desejado: repositório público não carrega ID de Pixel, e
ambiente local não deve sujar os dados da conta com eventos de teste.

### 9.2 O código base — [pronto]

Função `tc_meta_pixel_base()`, impressa em `wp_head`. Dispara dois eventos
no carregamento:

- `PageView` — padrão
- `ViewContent` — com `content_name`, `value: 197.00` e `currency: 'BRL'`

### 9.3 Os eventos de conversão — [pronto]

Função `tc_meta_pixel_events()`, em `wp_footer`. Usa um único listener
delegado no documento:

- clique em link para `pay.hotmart.com` → `InitiateCheckout`
- clique em link para `wa.me` → `Contact`

Por delegação, e não por listener em cada botão: a página tem três CTAs de
compra e dois de WhatsApp, e se você duplicar uma seção no Elementor o
novo botão já funciona, sem tocar em código.

### 9.4 Validar — [você]

- [ ] Instalar a extensão **Meta Pixel Helper** no Chrome
- [ ] Abrir a página e confirmar `PageView` e `ViewContent`
- [ ] Clicar num botão de compra e confirmar `InitiateCheckout`

No ambiente local o Pixel Helper só enxerga a página se você estiver
acessando pelo domínio `.local` do LocalWP, não por `127.0.0.1`.

---

## 10. Performance — [você] ~1h a 2h

Este é o passo que consome tempo. Faça na ordem.

### 10.1 Experiments do Elementor

**Elementor → Configurações → Recursos:**

- [ ] `Optimized DOM Output` → **Ativo**
- [ ] `Improved CSS Loading` → **Ativo**
- [ ] `Inline Font Icons` → **Ativo**
- [ ] `Optimized Gutenberg Loading` → **Ativo**
- [ ] `Element Caching` → **Ativo** (se a sua versão oferecer)

Os nomes mudam entre versões do Elementor. Se algum não existir no seu
painel, siga — nenhum é obrigatório para a página funcionar.

### 10.2 O que o child theme já faz — [pronto]

Não precisa de plugin de otimização para isto:

| Item | Ganho aproximado |
|---|---|
| Script de emoji removido | ~12KB de JS e uma conexão a `s.w.org` |
| CSS do Gutenberg removido | ~28KB de CSS não usado |
| `wp-embed` removido | ~1,5KB |
| Font Awesome removido | **~70KB** no caminho crítico |
| Google Fonts bloqueado, Inter local | 2 conexões externas a menos |
| Preload de fonte e do hero | LCP mais cedo |
| `aspect-ratio` em toda imagem | CLS próximo de zero |
| Estilo em CSS único, não por widget | menos arquivos, melhor cache |

O Font Awesome é o maior item da lista, e só pôde ser removido porque o
template foi construído sem nenhum widget de ícone: os check e os ícones
dos cards são SVG inline em `background-image`, e as estrelas dos
depoimentos usam `star_unicode` em vez de `star_fontawesome`.

### 10.3 Cache e compressão

- [ ] Instalar **LiteSpeed Cache** ou **W3 Total Cache**
- [ ] Ativar: cache de página, minificação de CSS e JS, compressão GZIP/Brotli
- [ ] **Não** ativar "Combine CSS/JS" sem testar — quebra o Elementor com
      frequência
- [ ] **Não** ativar lazy load de imagem para a imagem do hero; ela precisa
      carregar com prioridade

Em ambiente local o plugin de cache dá ganho menor, porque não há latência
de rede. O ganho real aparece depois de publicar.

### 10.4 Medir

**Ressalva importante sobre medir em ambiente local.**

O PageSpeed Insights precisa de uma URL pública. O LocalWP oferece o
`Live Link`, que expõe o site por um túnel — mas o túnel adiciona latência
e **a nota sai pior do que a real**. Não use Live Link para gerar o print
do portfólio: você vai medir o túnel, não a sua página.

Enquanto estiver local, meça com **Lighthouse no Chrome DevTools**, que é
o mesmo motor do PageSpeed:

- [ ] Abrir a página no Chrome
- [ ] `F12` → aba **Lighthouse**
- [ ] Device: **Mobile**
- [ ] Categories: apenas **Performance** (as outras depois)
- [ ] `Analyze page load`
- [ ] Repetir 3 vezes e considerar a mediana — a primeira execução sempre
      sai pior por causa do cache frio

- [ ] Meta: **Performance ≥ 90 no Mobile**
- [ ] Rodar também **Accessibility** e conferir que passa de 95 (contraste
      e foco já foram tratados; o que costuma faltar é `alt` de imagem)

Só depois de publicar num domínio real:

- [ ] Rodar o PageSpeed Insights em https://pagespeed.web.dev
- [ ] Print da aba **Celular** com a nota, para o portfólio
- [ ] Salvar em `assets/pagespeed-mobile.png`

---

## 11. QA antes de considerar pronto — [você] ~20 min

### Funcional

- [ ] Os 3 botões de compra abrem o link do checkout em nova aba
- [ ] O botão flutuante de WhatsApp abre o `wa.me` com a mensagem
      já preenchida
- [ ] O botão "Tenho uma dúvida antes de comprar" abre o WhatsApp
- [ ] O acordeão do FAQ abre e fecha nas 8 perguntas
- [ ] A barra fixa de oferta aparece **só** no celular e o botão dela
      funciona

### Visual

- [ ] Testar em 360px, 768px e 1440px de largura
- [ ] A barra fixa não cobre o texto do rodapé (há `padding-bottom: 96px`
      reservado para isso)
- [ ] O botão de WhatsApp não fica em cima da barra de oferta no celular
      (ele sobe para `bottom: 80px`)
- [ ] Nenhum texto estourando o container no celular
- [ ] Os 6 ícones dos cards de benefício aparecem

### Texto

- [ ] **Toda acentuação correta.** Procurar na página por `nao`, `voce`,
      `garantia` sem acento, `Duvidas`. Acento faltando em texto visível é
      regressão de produto, não detalhe de encoding
- [ ] O aviso de demonstração está visível no rodapé
- [ ] O aviso de "depoimentos de exemplo" está visível na seção de
      depoimentos

Os dois últimos itens não são opcionais. A página inventa um produto, um
instrutor e quatro depoimentos. Sem o aviso, é propaganda enganosa com a
sua assinatura embaixo; com o aviso, é peça de portfólio.

### Acessibilidade

- [ ] Navegar a página inteira só com `Tab` e confirmar contorno de foco
      visível em todo link e botão
- [ ] O primeiro `Tab` revela o link "Pular para o conteúdo"
- [ ] Toda imagem tem texto alternativo preenchido

---

## 12. Quando for publicar — [você] ~1h

Fora do escopo desta sessão, mas registrado para quando você decidir:

- [ ] Contratar hospedagem com WordPress
- [ ] Criar o subdomínio `demo1.seudominio.com.br`
- [ ] Exportar do LocalWP: botão direito no site → `Export`
- [ ] Importar na hospedagem, ou usar o plugin **All-in-One WP Migration**
- [ ] Confirmar SSL ativo (Let's Encrypt, incluso em qualquer hospedagem
      decente)
- [ ] Rodar o PageSpeed real e tirar o print
- [ ] **Deixar o site como "não indexável"** em Configurações → Leitura:
      é página de demonstração com produto fictício, e você não quer que
      ela ranqueie no Google competindo com o seu portfólio real
