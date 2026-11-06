# Status: demo-landing-infoproduto

## Fase atual: Montado e validado no LocalWP — aguardando imagens e medição de performance
## Último agente: Claude principal (sessão de 2026-11-06)
## Branch: main

---

## Contexto

Projeto 1 de 4 do conjunto de demonstrações para captação no 99Freelas.
Decisões tomadas pelo dev no início da sessão de 2026-11-06:

- **Hospedagem:** começar local (LocalWP), publicar depois
- **Montagem WordPress:** template Elementor em JSON, importado pelo dev
- **Projeto 4:** fluxo n8n simulado, sem WhatsApp real
- **Organização:** um repositório Git por projeto (pedido durante a sessão)

Estimativa apresentada e aceita: **24h a 32h** para os 4 projetos, sendo
16–22h de trabalho do assistente e 8–12h manuais do dev.

---

## Sessão 2026-11-06 ✅

### Concluído

- Estrutura do repositório e `git init` em `main` ✅
- `docs/copy.md` — copy das 9 seções, com as objeções mapeadas ✅
- `build/check-contrast.mjs` — verificador WCAG 2.1, executado ✅
- `docs/identidade-visual.md` — paleta, tipografia, espaçamento, com os
  ratios reais medidos ✅
- `build/content.json` — todo o texto pt-BR separado da estrutura ✅
- `build/elementor-kit.mjs` — construtor de template, sem dependências ✅
- `build/generate-elementor.mjs` — gerador com validação integrada ✅
- `elementor/landing-treino-30-dias.json` — gerado e validado:
  10 seções, 45 containers, 82 widgets, 127 elementos, 51,1KB ✅
- `child-theme/style.css` — visual completo, mobile-first ✅
- `child-theme/functions.php` — Pixel da Meta, WhatsApp flutuante, barra de
  oferta, remoção de assets não usados, preload de fonte e hero ✅
- `README.md`, `CLAUDE.md`, `docs/checklist-publicacao.md` ✅

### Verificações executadas

| O que | Como | Resultado |
|---|---|---|
| Contraste da paleta | `node build/check-contrast.mjs` | 10 pares, nenhuma falha inesperada |
| Validade do template | validação dentro do gerador | 127 ids únicos, nenhum problema |
| JSON parseia | `JSON.parse` do arquivo gerado | OK |
| Widgets usados | varredura da árvore | heading 28, text-editor 43, button 4, image 2, star-rating 4, accordion 1 |
| Encoding | `file` + grep de acentuadas | UTF-8 em todos; acentuação íntegra |
| Estrutura do PHP | checagem de balanceamento e hooks | chaves, parênteses e hooks consistentes |

### Correções feitas durante a sessão

- **Hero no celular:** a primeira versão escondia a imagem com
  `display:none`, mas o comentário dizia que ela apareceria depois do
  texto. Além da divergência, `display:none` em `<img>` ainda provoca o
  download em vários navegadores — pagaria a banda sem mostrar nada.
  Trocado por uma coluna única com `aspect-ratio` 16/10 no mobile, usando
  a ordem natural do DOM.
- **Estrelas dos depoimentos:** `star_fontawesome` trocado por
  `star_unicode`. O primeiro reintroduziria o Font Awesome que o
  `functions.php` remove de propósito.

---

## Blockers

Nenhum blocker de código. O que falta depende de ação manual:

- LocalWP não instalado ainda — a máquina não tem PHP, MySQL nem Docker,
  então não há como validar o PHP nem medir performance antes disso
- Arquivos `inter-400.woff2` e `inter-700.woff2` ausentes (binário não vai
  para o repositório). A página não quebra sem eles: cai para a fonte do
  sistema

---

## Pendências técnicas

- `php -l` não foi executado — não há PHP na máquina. A validação do
  `functions.php` foi estrutural (balanceamento, hooks, escapes). A
  validação real acontece ao abrir o site no LocalWP
- Imagens do hero e do instrutor são placeholder do Elementor. Trocar por
  WebP nas dimensões de `docs/identidade-visual.md`
- `hero_image` em `tc_config()` está vazio; preencher depois de subir a
  imagem real, senão o preload do LCP não acontece
- Nota de PageSpeed não medida. Medir com Lighthouse local enquanto estiver
  no LocalWP; o Live Link adiciona latência de túnel e dá nota pior que a
  real
- A capa do case no portfólio é um SVG ilustrativo, não screenshot. Trocar
  pelo print real depois do passo 11 do checklist, e aí acrescentar o campo
  `demo` na entrada de `src/data/projetos.js`

---

## Próximo passo

Duas frentes, independentes entre si:

1. **Dev:** executar `docs/checklist-publicacao.md` passos 1 a 5
   (LocalWP, Hello Elementor, child theme, criar página, importar
   template). Cerca de 40 min. É o que confirma que o template importa
   limpo e que o PHP não tem erro de sintaxe.

2. **Assistente:** iniciar o Projeto 2 (`demo-site-institucional`,
   Clínica de Fisioterapia Movimento) — 4 páginas, paleta teal
   deliberadamente oposta à desta demo, mapa e WhatsApp flutuante. Não
   depende do passo 1.

---

## Publicação — 2026-11-06 (mesma sessão)

### Repositório

- `https://github.com/bbigelli/demo-landing-infoproduto` — público, branch
  `main`, 16 arquivos
- Varredura por segredos antes de publicar: nenhum. Número de WhatsApp é
  placeholder, checkout é `EXEMPLO-DEMO`, ID do Pixel fora do repositório

### Case no portfólio

Publicado em `bbigelli/meu-portifolio`, commit `1d731d1`:

- entrada em `src/data/projetos.js` seguindo o contrato existente
- capa em `src/assets/images/Capa-Landing-Infoproduto.svg`
- sem campo `demo` (o repo está publicado, a página ainda não)
- validado com `CI=true npm run build`: compila sem warning, SVG emitido em
  `static/media`, projeto presente no bundle

### Defeito encontrado e corrigido no portfólio

O `README.md` documentava uma stack que o projeto não usa. Era Create React
App descrito como Vite:

| Dizia | Real |
|---|---|
| badge e tabela: Vite 5 | `react-scripts` 5 (CRA) |
| `npm run dev` | `npm start` |
| porta 5173 | porta 3000 |
| `VITE_FORM_ID` | `REACT_APP_FORM_ID` |

O último item era o mais grave: `Contato.jsx` lê `REACT_APP_FORM_ID`, então
quem clonasse e seguisse o README ficava com o formulário de contato
quebrado sem aviso. Corrigidos também o typo "Artesatanos" e uma célula de
tabela com pipe duplicado.

Não corrigido, deixado para decisão do dev: o README linka `LICENSE`, e o
arquivo não existe no repositório.

### Correção de autoria dos commits

O commit inicial do repo 1 saiu com o e-mail do perfil do Manager
(`marcoaurelioexp@...`) porque foi passado explicitamente via `-c
user.email`, sobrescrevendo a config global, que já estava correta. O dev
pediu a correção.

- `--amend --reset-author` + `push --force-with-lease` corrigiu o branch
- mas o commit antigo ficou **órfão e ainda acessível por SHA** na API do
  GitHub, com o e-mail antigo. Force push não remove o objeto
- resolvido deletando e recriando o repositório (o dev deletou pela
  interface; o token não tem o scope `delete_repo`). O SHA antigo agora
  retorna `422 No commit found`
- identidade fixada em `git config user.*` nos dois repos locais

Registrado como memória de feedback para não repetir.

### Próximo passo revisado

1. **Dev:** passos 1 a 5 do checklist no LocalWP (~40 min). Recomendado
   ativar antes, em Settings > Emails do GitHub: "Keep my email addresses
   private" e "Block command line pushes that expose my email"
2. **Assistente:** Projeto 2 (`demo-site-institucional`)

---

## Montagem no LocalWP — 2026-11-06 (mesma sessão)

Feita com automação de navegador, a pedido do dev. O site já estava criado
por ele, com Hello Elementor, child theme e Elementor instalados.

### Validado no ambiente real

| O que | Resultado |
|---|---|
| `functions.php` | Carrega sem erro. O painel abriu normalmente e o tema ativou — erro de sintaxe daria tela branca |
| Botão de WhatsApp | Renderiza no verde escuro `#128C7E`, inclusive dentro do editor |
| Import do template | 10 seções, 127 elementos, sem erro |
| Console do navegador | Zero erros e zero 404. Só a mensagem informativa do jQuery Migrate |
| Layout final | 6 cards em 3 colunas, semanas e depoimentos em 2, hero lado a lado |

### Três defeitos encontrados e corrigidos

Nenhum deles apareceria em validação estática. Detalhes técnicos no commit
`c61459e`.

1. **Classes não chegavam aos containers.** O controle chama-se
   `css_classes` em container e `_css_classes` em widget. Usávamos a forma
   com underscore para os dois, então os 82 widgets funcionavam e os 45
   containers não — sem seção, sem grade, sem card.
2. **Container boxed quebra grid.** `content_width: boxed` renderiza um
   `.e-con-inner` entre o container e os filhos, e o `display: grid` caía
   num elemento de um filho só. Todos os containers passaram a `full`.
3. **Ícone do card sobrepunha o título.** O Elementor usa o `::before` dos
   containers para o overlay de fundo, com `position: absolute`. Corrigido
   com `position: static`.

Além disso, o kit global do Elementor pintava título de azul e botão de
verde. As cores passaram a ser declaradas com o prefixo `.elementor`, que
vence por especificidade em vez de depender da ordem de carregamento.

### Configuração aplicada na página

- Layout **Elementor Canvas**, para remover cabeçalho, rodapé e título do
  tema. Documentado como passo 7 do guia do LocalWP

### Pendências que surgiram

- Há 3 templates `Landing - Treino em Casa 30 Dias` na biblioteca do
  Elementor (ids 13, 18 e 28), das três rodadas de correção. O válido é o
  **28**. Os outros dois podem ser apagados em Modelos → Modelos salvos
- Imagens continuam placeholder
- Nota de PageSpeed ainda não medida

### Próximo passo

1. **Dev:** apagar os templates 13 e 18, trocar as imagens e rodar o
   Lighthouse (DevTools → Lighthouse → Mobile → Performance)
2. **Assistente:** Projeto 2, já com os três defeitos acima corrigidos no
   gerador — não se repetem
