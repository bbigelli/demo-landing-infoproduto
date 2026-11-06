# Status: demo-landing-infoproduto

## Fase atual: Entregáveis de código concluídos — aguardando montagem no LocalWP
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
- Nenhum commit feito ainda nesta sessão — arquivos em stage, aguardando

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
