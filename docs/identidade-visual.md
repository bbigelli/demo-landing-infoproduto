# Identidade visual — Demo 1 "Treino em Casa 30 Dias"

Direção: **energia e urgência**, próprio de infoproduto com tráfego pago.
A Demo 2 (Clínica de Fisioterapia Movimento) usará uma direção deliberadamente
oposta — teal frio, muito espaço em branco, tom calmo —, para que as duas
peças do portfólio não pareçam o mesmo site repintado.

Todos os pares de cor abaixo foram medidos com `build/check-contrast.mjs`
(WCAG 2.1, fórmula oficial de luminância relativa). Os números são reais,
não estimativas.

---

## Paleta

| Token | Hex | Uso |
|---|---|---|
| `ink` | `#18181B` | Texto principal, títulos |
| `ink-soft` | `#52525B` | Texto de apoio, legendas |
| `surface` | `#FFFFFF` | Fundo padrão |
| `surface-alt` | `#FAFAF9` | Fundo de seções alternadas |
| `surface-dark` | `#1C1917` | Seções escuras (oferta, fechamento) |
| `primary` | `#C2410C` | Botões de compra, links |
| `primary-hover` | `#9A3412` | Estado hover e foco do botão |
| `accent` | `#F59E0B` | Selos e destaques — **somente sobre fundo escuro** |
| `star` | `#B45309` | Estrelas dos depoimentos, sobre card branco |
| `success` | `#15803D` | Ícones de check, selo de garantia |
| `whatsapp` | `#075E54` | Botão flutuante de WhatsApp |

## Contraste medido

| Par | Ratio | Mínimo AA | Resultado |
|---|---|---|---|
| Texto corpo sobre branco | 17,72:1 | 4,5 | AAA |
| Texto de apoio sobre branco | 7,73:1 | 4,5 | AAA |
| Texto corpo sobre `surface-alt` | 16,96:1 | 4,5 | AAA |
| Branco sobre botão `primary` | 5,18:1 | 4,5 | AA |
| Branco sobre `primary-hover` | 7,31:1 | 4,5 | AAA |
| Branco sobre `surface-dark` | 17,49:1 | 4,5 | AAA |
| `accent` sobre `surface-dark` | 8,14:1 | 4,5 | AAA |
| `success` sobre branco | 5,02:1 | 4,5 | AA |
| `star` sobre card branco | 5,02:1 | 4,5 | AA |
| Texto branco sobre `whatsapp` | 7,67:1 | 4,5 | AAA |
| Separador do rodapé `#78716C` | 4,59:1 | 4,5 | AA |

### Três restrições que saíram da medição

**1. O âmbar `#F59E0B` nunca vai sobre fundo claro.** Mede 2,15:1 —
reprovado com folga. É a cor dos selos, e selo sobre fundo claro é o erro
mais comum nesse tipo de página. Sobre `surface-dark` ele mede 8,14:1 e
funciona bem. Quando o selo precisar aparecer em seção clara, usar
`primary` no texto com fundo `#FFF7ED`. As estrelas dos depoimentos ficam
sobre card branco, então usam `star` (`#B45309`, 5,02:1), que mantém o tom
dourado e passa.

**2. O verde clássico do WhatsApp `#25D366` foi descartado.** Ícone branco
sobre ele mede 1,98:1.

**3. O botão de WhatsApp precisa de 4,5, não de 3,0.** O `#128C7E` chegou a
ser adotado porque mede 4,14:1, o que passaria no critério de **componente
gráfico** da WCAG 1.4.11. Mas o botão não é só um ícone: tem o rótulo
"Falar no WhatsApp", e texto exige 4,5. Trocado pelo `#075E54`, o verde
escuro do cabeçalho do aplicativo, que mede 7,67:1. A lição é escolher o
critério pelo conteúdo real do componente, não pela ideia que se tem dele.

---

## Como auditar o contraste de verdade

**Passar no `check-contrast.mjs` é necessário, mas não é suficiente.**

Em 2026-11-06 o Lighthouse acusou **74 elementos** com contraste
insuficiente numa página cuja paleta passava limpa no script. A paleta
estava certa; errado estava o que o navegador pintava — o kit global do
Elementor aplicava `#7A7A7A` (4,29:1) em todo widget de texto, por cima do
nosso CSS.

O script vê as cores que escolhemos. Ele não vê as que um plugin injeta.

Então o procedimento tem duas etapas:

1. **Ao escolher cores:** `node build/check-contrast.mjs`
2. **Antes de dar como pronto:** auditar a página renderizada no navegador

Na segunda etapa, varra todo elemento de texto visível, calcule o contraste
contra o fundo **composto** — subindo a árvore até achar uma cor opaca e
compondo as camadas com alpha pelo caminho — e compare com o mínimo correto
para aquele tamanho e peso: 3,0 para texto grande (≥24px, ou ≥18,66px em
negrito) e 4,5 para o resto.

Dois detalhes que produziram um falso positivo e um falso negativo na
primeira tentativa:

- **Compor o alpha.** Ignorar o canal alfa faz um selo com
  `rgba(245,158,11,0.14)` sobre fundo escuro ser lido como âmbar sobre
  âmbar, ratio 1,00.
- **Excluir a barra de administração.** Logado no WordPress, o
  `#wpadminbar` entra na varredura e reporta falhas que o visitante nunca vê.

Meta: zero falha na página pública. Última auditoria: 159 elementos
testados, nenhuma falha.

---

## Tipografia

**Família única: Inter**, pesos 400 e 700 apenas.

A escolha é de performance, não de gosto. Cada peso extra é um arquivo de
fonte a mais no caminho crítico, e a meta da página é PageSpeed acima de 90
no celular. Dois pesos cobrem a hierarquia inteira — títulos em 700, corpo
em 400 — e a família é hospedada localmente, nunca pelo CDN do Google.

Isso é configurado em **Elementor → Configurações → Recursos → Google Fonts:
hospedar localmente**. Sem isso, a página abre conexão com `fonts.gstatic.com`
e perde pontos em "Reduzir o tempo de resposta inicial do servidor".

### Escala (mobile-first)

| Elemento | Mobile | Desktop | Peso | Entrelinha |
|---|---|---|---|---|
| H1 | 32px | 56px | 700 | 1,1 |
| H2 | 26px | 40px | 700 | 1,2 |
| H3 (card) | 20px | 22px | 700 | 1,3 |
| Corpo | 16px | 18px | 400 | 1,6 |
| Corpo grande (subtítulo do hero) | 18px | 20px | 400 | 1,5 |
| Legenda e aviso legal | 13px | 14px | 400 | 1,5 |
| Botão | 17px | 18px | 700 | 1,2 |

Corpo nunca abaixo de 16px no celular — abaixo disso o Safari no iOS dá zoom
automático em campos de formulário e a leitura cansa.

---

## Espaçamento e layout

Escala de 8px: `8, 16, 24, 32, 48, 64, 96, 128`.

| Contexto | Mobile | Desktop |
|---|---|---|
| Padding vertical de seção | 48px | 96px |
| Padding horizontal da página | 20px | 24px |
| Largura máxima do conteúdo | — | 1140px |
| Largura máxima de bloco de texto | — | 680px |
| Espaço entre cards | 16px | 24px |

Bloco de texto corrido limitado a 680px: linha mais longa que isso faz o olho
perder o início da linha seguinte.

**Grade de cards por breakpoint:** 1 coluna no celular, 2 no tablet, 3 no
desktop. Os seis cards de benefício viram duas fileiras de três no desktop.

---

## Botões

| Propriedade | Valor |
|---|---|
| Altura mínima | 56px (alvo de toque confortável) |
| Padding | 18px vertical, 36px horizontal |
| Raio da borda | 8px |
| Largura no mobile | 100% |
| Sombra | `0 4px 14px rgba(194, 65, 12, 0.25)` |
| Transição | `background-color .2s ease` |
| Foco visível | contorno de 3px em `primary-hover`, deslocado 2px |

Altura mínima de 56px vem da recomendação de alvo de toque: o mínimo da WCAG
é 44px, e 56px é o que funciona de fato para o polegar em botão de compra.

Foco visível é obrigatório — Elementor não entrega isso por padrão e sem ele
a página fica inutilizável por teclado.

---

## Imagens

| Slot | Proporção | Tamanho de entrega | Formato |
|---|---|---|---|
| Hero | 4:3 | 960×720 | WebP + JPG de fallback |
| Instrutor | 3:4 | 600×800 | WebP |
| Mockup de conteúdo | 1:1 | 700×700 | WebP |
| Avatar de depoimento | 1:1 | 96×96 | WebP |
| Ícones | — | — | SVG inline |

Regras que valem a nota no PageSpeed:

- A imagem do hero é a única com `loading="eager"` e `fetchpriority="high"`.
  Ela é o LCP da página; adiar o carregamento dela é adiar a nota.
- Todas as outras recebem `loading="lazy"`.
- Toda imagem leva `width` e `height` explícitos, para não gerar deslocamento
  de layout (CLS).
- Ícones são SVG inline, não biblioteca de fontes. Carregar o Font Awesome
  inteiro para usar oito ícones custa cerca de 70KB no caminho crítico.

---

## Modo escuro

Fora de escopo. A página é peça de conversão de campanha paga, com uma única
direção visual controlada. Suporte a modo escuro aqui só adicionaria risco de
seção escura com texto escuro.
