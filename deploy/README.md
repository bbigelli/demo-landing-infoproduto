# deploy/

Arquivos copiados para `site-dist/` pelo `build/prune-static.mjs`, no fim da
poda. Ficam aqui porque o `site-dist/` e recriado do zero a cada execucao.

## vercel.json

- **Imagens e fontes**: cache de um ano com `immutable`. Seguro porque o
  conteudo desses arquivos nao muda; se mudar, muda o nome.
- **CSS e JS**: `max-age=0, must-revalidate`. NAO use `immutable` aqui.
  O `style.css` do tema tem URL estavel, sem hash no nome, e o WordPress
  versiona pela query string (`?ver=1.0.0`). Com `immutable`, navegador e
  CDN seguram a versao antiga por um ano e nenhuma correcao de CSS chega
  ao visitante -- aconteceu em 2026-11-06, o HTML atualizou e o CSS nao.
  Com `must-revalidate` o navegador pergunta ao servidor e recebe 304
  quando nada mudou, que custa quase nada.
- **Tudo**: cabecalhos de seguranca e `X-Robots-Tag: noindex`. O noindex e
  intencional: e demo com produto ficticio e nao deve ranquear competindo
  com o portfolio real.

Ao alterar o `child-theme/style.css`, suba tambem a linha `Version:` do
cabecalho do arquivo. E o que muda o `?ver=` e garante a invalidacao no
navegador do visitante.
