# Landing page de infoproduto — WordPress + Elementor

Peça de portfólio: landing page de venda para curso online, construída em
WordPress com Elementor, com foco em conversão e em performance no celular.

> **Produto fictício.** "Treino em Casa 30 Dias" não existe, o instrutor é
> um personagem e os depoimentos são exemplos ilustrativos — e a própria
> página diz isso, em aviso visível no rodapé e na seção de depoimentos.
> Este repositório é demonstração técnica, não material de venda.

---

## O que está aqui

| Pasta | Conteúdo |
|---|---|
| `docs/copy.md` | Copy completa das 9 seções, com as objeções que cada uma derruba |
| `docs/identidade-visual.md` | Paleta, tipografia, espaçamento e contraste medido |
| `docs/checklist-publicacao.md` | Passo a passo de montagem, do zero ao print do PageSpeed |
| `build/content.json` | Todo o texto da página, separado da estrutura |
| `build/elementor-kit.mjs` | Construtor de templates do Elementor, sem dependências |
| `build/generate-elementor.mjs` | Gera e **valida** o template importável |
| `build/check-contrast.mjs` | Verificador de contraste WCAG da paleta |
| `elementor/landing-treino-30-dias.json` | Template pronto para importar |
| `child-theme/` | Child theme de Hello Elementor: visual, Pixel, WhatsApp |

## Montar

```bash
node build/generate-elementor.mjs   # gera o template
node build/check-contrast.mjs       # confere a paleta
```

Zero dependências — só Node 18+. Não há `package.json` porque não há o que
instalar.

Para subir a página num WordPress, siga `docs/checklist-publicacao.md`.

---

## Três decisões técnicas que definem este projeto

### 1. O estilo não está dentro do template do Elementor

O Elementor permite gravar cada propriedade de tipografia e espaçamento
dentro das configurações de cada widget. Isso produz um JSON gigante e
frágil, e faz o Elementor emitir um arquivo CSS por página.

Aqui os widgets carregam apenas **classes semânticas** (`tc-h1`,
`tc-card`, `tc-btn--primary`) e todo o visual vive em
`child-theme/style.css`. Resultado: template de 51KB em vez de algumas
centenas, um único CSS cacheável, e o cliente continua editando cada texto
normalmente dentro do Elementor.

### 2. Nenhuma biblioteca de ícones

Font Awesome custa cerca de **70KB no caminho crítico** para desenhar
poucos ícones. A página foi construída sem nenhum widget de ícone: os
check das listas e os seis ícones dos cards são SVG inline em
`background-image`, e as estrelas dos depoimentos usam `star_unicode` em
vez de `star_fontawesome`.

Com isso o `functions.php` pode remover a biblioteca com segurança, junto
com o script de emoji (~12KB), o CSS do Gutenberg (~28KB) e o `wp-embed`.

### 3. Contraste é medido, não estimado

`build/check-contrast.mjs` aplica a fórmula oficial da WCAG 2.1 em cada par
de cor da paleta. Duas decisões saíram dessa medição:

- O âmbar `#F59E0B` mede **2,15:1** sobre branco e está reprovado. Ele só
  aparece sobre fundo escuro, onde mede 8,14:1.
- O verde clássico do WhatsApp `#25D366` foi descartado: ícone branco sobre
  ele mede **1,98:1**, abaixo do mínimo de 3:1 que a WCAG 1.4.11 exige para
  componente gráfico. O botão usa o verde escuro oficial `#128C7E`, que
  mede 4,14:1.

Rode o script e confira — os números do `docs/identidade-visual.md` saíram
dele.

---

## Stack

- WordPress 6.x
- Elementor **gratuito** (nenhum widget Pro — demo que exige licença paga é
  demo que não se entrega)
- Hello Elementor como tema pai
- Child theme próprio para visual, Pixel da Meta e WhatsApp
- Node 18+ apenas para gerar o template

## Onde o Pixel da Meta é configurado

O ID fica em `wp-config.php`, nunca no repositório:

```php
define( 'TC_META_PIXEL_ID', '000000000000000' );
```

Sem a constante, nenhum script de rastreamento é impresso. O código base
está em `tc_meta_pixel_base()` e os eventos de conversão em
`tc_meta_pixel_events()`, que usa um listener delegado para disparar
`InitiateCheckout` em qualquer link de checkout e `Contact` em qualquer
link de WhatsApp — inclusive em botões criados depois.

Detalhes em `docs/checklist-publicacao.md`, passo 9.

---

## Reaproveitar para um cliente real

O gerador foi escrito para isso. Troque `build/content.json`, rode
`node build/generate-elementor.mjs` e ajuste os tokens no topo de
`child-theme/style.css`. A estrutura das 9 seções serve para qualquer
infoproduto; o que muda é texto, cor e imagem.

---

Desenvolvido por **Bruno Bigelli** · [github.com/bbigelli](https://github.com/bbigelli)
