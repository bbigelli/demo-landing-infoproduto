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
| `success` | `#15803D` | Ícones de check, selo de garantia |
| `whatsapp` | `#128C7E` | Botão flutuante de WhatsApp |

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
| Ícone branco sobre `whatsapp` | 4,14:1 | 3,0 (ícone) | passa |

### Duas restrições que saíram da medição

**1. O âmbar `#F59E0B` nunca vai sobre branco.** Mede 2,15:1 — reprovado
com folga. É a cor dos selos, e selo sobre fundo claro é o erro mais comum
nesse tipo de página. Sobre `surface-dark` ele mede 8,14:1 e funciona bem.
Quando o selo precisar aparecer em seção clara, usar `primary` no texto com
fundo `#FFF7ED`.

**2. O verde clássico do WhatsApp `#25D366` foi descartado.** Ícone branco
sobre ele mede 1,98:1, abaixo do mínimo de 3:1 que a WCAG 1.4.11 exige para
componente gráfico. Trocado pelo verde escuro oficial da marca `#128C7E`,
que mede 4,14:1. Continua reconhecível como WhatsApp e passa no critério.

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
