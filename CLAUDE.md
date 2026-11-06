# CLAUDE.md — Demo 1: Landing de infoproduto

Complementa o CLAUDE.md global. Só o que é específico deste repositório.

## O que este projeto é

Peça de portfólio para captação no 99Freelas, não produto. O objetivo é
demonstrar competência em WordPress, Elementor, conversão e performance
mobile. Um dos quatro repositórios de demonstração:

| Repo | Demonstra |
|---|---|
| `demo-landing-infoproduto` | este — landing de curso online |
| `demo-site-institucional` | site de profissional liberal, 4 páginas |
| `demo-antes-depois-wordpress` | ajuste de layout e otimização |
| `demo-automacao-whatsapp-ia` | fluxo n8n com IA |

## Regras permanentes

### O aviso de demonstração não sai

A página inventa um produto, um instrutor e quatro depoimentos. Os dois
avisos — o do rodapé (`footer.demoNotice`) e o da seção de depoimentos
(`testimonials.disclaimer`) — são obrigatórios e visíveis.

Sem eles, a peça deixa de ser portfólio e passa a ser propaganda enganosa
assinada pelo desenvolvedor. Nenhuma task de "limpar a página" ou "deixar
mais vendedora" autoriza removê-los.

### Elementor gratuito apenas

Nenhum widget Pro. Demo que exige licença paga é demo que não se entrega
para o cliente que não quer pagar licença — e a maioria no 99Freelas não
quer. Se faltar um recurso, resolver com CSS no child theme.

### Texto vive em `build/content.json`, não no gerador

`build/generate-elementor.mjs` não contém nenhuma string em português.
Essa separação é o que permite reaproveitar o gerador para um cliente real
trocando só o JSON. Ao acrescentar seção, o texto vai no `content.json`.

### Estilo vive no child theme, não nas settings do widget

Widgets carregam classes semânticas (`tc-h1`, `tc-card`, `tc-btn--primary`)
e o visual fica em `child-theme/style.css`. Não embutir tipografia ou
espaçamento nas settings do Elementor: infla o JSON e faz o Elementor
emitir CSS por página.

### Nenhuma biblioteca de ícones

Font Awesome foi removido no `functions.php` e os ícones são SVG inline em
`background-image`. Ao acrescentar ícone, seguir o padrão `.tc-icon-*` do
`style.css`. Adicionar um widget de ícone do Elementor recoloca os ~70KB
no caminho crítico e invalida a nota de PageSpeed da demo.

### Contraste é medido antes de entrar

Nova cor passa por `node build/check-contrast.mjs` e o resultado vai para
`docs/identidade-visual.md`. Dois valores já reprovados e documentados: o
âmbar sobre branco (2,15:1) e o verde claro do WhatsApp com ícone branco
(1,98:1).

### O ID do Pixel nunca entra no repositório

Fica em `TC_META_PIXEL_ID`, definida no `wp-config.php`. Sem a constante,
`tc_meta_pixel_base()` não imprime nada — é o comportamento correto para
repositório público e para ambiente local.

## Ambiente

- Desenvolvimento em **LocalWP** (a máquina não tem PHP, MySQL nem Docker)
- Node 18+ só para gerar o template; zero dependências, sem `package.json`
- Não há PHP na máquina, então `php -l` não roda aqui: alteração em
  `functions.php` é validada abrindo o site no LocalWP

## Pointers

- `docs/copy.md` — copy e as objeções que cada seção derruba
- `docs/identidade-visual.md` — tokens e contraste medido
- `docs/checklist-publicacao.md` — montagem do zero até o print do PageSpeed
- `docs/demo-landing-infoproduto-status.md` — estado atual e próximo passo
