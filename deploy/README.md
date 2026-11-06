# deploy/

Arquivos copiados para `site-dist/` pelo `build/prune-static.mjs`, no fim da
poda. Ficam aqui porque o `site-dist/` e recriado do zero a cada execucao.

- `vercel.json` — cache longo para os assets com hash, cabecalhos de
  seguranca e `X-Robots-Tag: noindex`. O noindex e intencional: e demo com
  produto ficticio e nao deve ranquear competindo com o portfolio real.
