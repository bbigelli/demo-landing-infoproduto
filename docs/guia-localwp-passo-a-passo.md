# Guia detalhado do LocalWP — primeira vez

Complementa `docs/checklist-publicacao.md` com o detalhe de cada tela, para
quem nunca usou o LocalWP.

## A dúvida mais comum, respondida primeiro

**Você não extrai o ZIP do tema em lugar nenhum.** O WordPress recebe o ZIP
e extrai ele mesmo, na pasta certa. É assim que todo tema e todo plugin são
instalados.

Isso vale para os dois temas desta montagem:

| Arquivo | O que fazer |
|---|---|
| ZIP do Hello Elementor que você baixou | Enviar pelo painel — ou nem usar, e instalar pelo buscador do próprio WordPress, que é mais rápido |
| `dist/treino-casa-30.zip` deste repositório | Enviar pelo painel, do mesmo jeito |

O único momento em que você mexe em pasta é se quiser editar o `functions.php`
depois, para trocar o número do WhatsApp.

---

## Passo 1 — Criar o site no LocalWP

O LocalWP já está instalado. Agora o site, que ainda não existe.

### 1.1 Iniciar a criação

Na janela do LocalWP, clique no **`+`** no canto inferior esquerdo. Se a tela
estiver vazia, há um botão grande **Create a new site**.

### 1.2 Tela "What kind of site do you want to create?"

Escolha **Create a new site** e clique em **Continue**.

### 1.3 Tela "What's your site's name?"

- Digite: `demo-treino-casa`
- Se você clicar em **Advanced options**, verá o domínio local que será
  criado (`demo-treino-casa.local`) e a pasta onde o site vai morar.
  Não precisa mudar nada
- **Continue**

A pasta será `C:\Users\Bruno\Local Sites\demo-treino-casa\app\public`.
Guarde esse caminho — é a raiz do WordPress, equivalente ao `public_html`
de uma hospedagem.

### 1.4 Tela "Choose your environment"

Escolha **Preferred** e clique em **Continue**.

O Preferred já vem com PHP 8.x, nginx e MySQL 8, que é o que esta montagem
precisa. O modo Custom só serve para reproduzir a versão exata de uma
hospedagem específica, o que não é o caso aqui.

### 1.5 Tela "Set up WordPress"

- **WordPress username:** escolha um e **anote**
- **WordPress password:** escolha uma e **anote**
- **WordPress email:** o seu e-mail
- Em **Advanced options**, deixe **Multisite: No**
- Clique em **Add site**

> **Anote usuário e senha de verdade.** Você vai entrar no painel várias
> vezes durante a montagem, e recuperar senha em site local é mais chato do
> que parece — o WordPress tenta enviar um e-mail que não sai da máquina.

### 1.6 Espere a instalação

O LocalWP baixa o WordPress e sobe o ambiente. Na primeira vez leva de 1 a
3 minutos.

Duas coisas podem aparecer:

- **O Windows pede permissão de firewall** para o nginx ou o MySQL.
  Clique em **Permitir acesso**. Sem isso o site não abre
- **O LocalWP pede sua senha do Windows** para instalar o certificado SSL.
  Pode autorizar — é o que faz o site abrir em `https` sem aviso de
  segurança, e o Pixel da Meta só funciona direito em `https`

### 1.7 Confirme que funcionou

Com o site criado, aparecem dois botões no topo direito:

- **Open site** — abre a página pública (vai mostrar um tema padrão do
  WordPress, ainda sem nada nosso)
- **WP Admin** — abre o painel administrativo

Clique em **WP Admin** e entre com o usuário e a senha que você anotou.

Se abrir o painel, o passo 1 está concluído. Você vai usar esse painel para
tudo que vem a seguir.

---

## Passo 2 — Instalar o Hello Elementor

Este é o **tema pai**. Ele tem que entrar antes do nosso.

### Caminho mais fácil (sem usar o ZIP que você baixou)

No painel do WordPress:

1. Menu lateral → **Aparência** → **Temas**
2. Botão **Adicionar novo tema**, no alto
3. No campo de busca, digite `Hello Elementor`
4. No cartão do tema (autoria **Elementor Team**), clique em **Instalar**
5. **Não clique em Ativar ainda** — leia o aviso no fim deste passo

### Se preferir usar o ZIP que você já baixou

1. **Aparência** → **Temas** → **Adicionar novo tema**
2. Botão **Enviar tema**, ao lado do título
3. **Escolher arquivo** → selecione o ZIP do Hello Elementor
4. **Instalar agora**
5. **Não clique em Ativar ainda**

> **Por que não ativar agora:** quem fica ativo no final é o nosso child
> theme, não o Hello Elementor. O Hello precisa estar **instalado** para o
> nosso funcionar, mas não precisa estar ativo. Se você ativar o Hello e
> depois o nosso, o resultado é o mesmo — só não vale ativar o nosso antes
> de o Hello existir, porque aí o WordPress reclama que o tema pai está
> faltando.

---

## Passo 3 — Instalar o plugin Elementor

1. Menu lateral → **Plugins** → **Adicionar novo plugin**
2. Busque `Elementor`
3. No cartão **Elementor Website Builder** (autoria Elementor.com), clique
   em **Instalar agora**
4. Clique em **Ativar**
5. O Elementor abre uma tela de boas-vindas pedindo nome do site, tipo de
   negócio e sugerindo a versão Pro. **Pule tudo** — procure o link
   `Skip` ou feche a aba de onboarding

Use a **versão gratuita**. A página foi construída só com widgets do
Elementor grátis, de propósito: demo que exige licença paga é demo que você
não consegue entregar para a maioria dos clientes do 99Freelas.

---

## Passo 4 — Instalar o nosso child theme

O ZIP já está pronto no repositório:

```
J:\Projetos\demo-landing-infoproduto\dist\treino-casa-30.zip
```

Se ele não existir, ou se você mudar algo no `child-theme/`, gere de novo
abrindo o PowerShell na pasta do repositório e rodando:

```powershell
.\build\pack-theme.ps1
```

### Instalar

1. **Aparência** → **Temas** → **Adicionar novo tema**
2. Botão **Enviar tema**
3. **Escolher arquivo** → selecione `dist\treino-casa-30.zip`
4. **Instalar agora**
5. Agora sim: **Ativar**

### Confirme

Volte em **Aparência** → **Temas**. O cartão **Treino em Casa 30 Dias** deve
aparecer marcado como ativo.

### Se der erro

| Mensagem | Causa | O que fazer |
|---|---|---|
| "O tema pai está faltando" / "parent theme is missing" | O Hello Elementor não foi instalado | Volte ao passo 2 |
| "A pasta do tema já existe" | Você está reinstalando | Clique em **Substituir o atual pelo enviado** |
| "O pacote não pôde ser instalado. Nenhum arquivo style.css válido" | O ZIP foi gerado errado | Rode `.\build\pack-theme.ps1`, que valida a estrutura antes de salvar |
| **Tela branca** depois de ativar | Erro de PHP no `functions.php` | **Me avise com urgência** — é o único arquivo que não pude validar, porque não há PHP nesta máquina. Veja abaixo como sair da tela branca |

### Como sair de uma tela branca

Não entre em pânico, e não reinstale o WordPress. O tema anterior continua
instalado:

1. No LocalWP, clique com o botão direito no site → **Open site shell**
2. Digite: `wp theme activate hello-elementor`
3. O site volta. Me mande a mensagem de erro que apareceu

Se o shell não tiver o `wp` disponível, dá para voltar renomeando a pasta
`wp-content/themes/treino-casa-30` — o WordPress cai no tema padrão sozinho
quando o tema ativo desaparece.

---

## Passo 5 — Criar a página e definir como inicial

### 5.1 Criar

1. Menu lateral → **Páginas** → **Adicionar nova página**
2. No título, escreva: `Treino em Casa 30 Dias`
3. Clique em **Publicar**, e confirme no **Publicar** que aparece na lateral
4. Confira o **permalink**. Deve ser `treino-em-casa-30-dias`. Se o
   WordPress gerou outro, clique nele e corrija

### 5.2 Definir como página inicial

1. Menu lateral → **Configurações** → **Leitura**
2. Em **Sua página inicial exibe**, marque **Uma página estática**
3. Em **Página inicial**, escolha **Treino em Casa 30 Dias**
4. **Salvar alterações**

> **Este passo não é cosmético.** O `functions.php` usa a função
> `tc_is_landing()` para decidir onde injetar o Pixel da Meta, o botão de
> WhatsApp e a barra de oferta. Essa função só responde "sim" na página
> inicial ou no slug `treino-em-casa-30-dias`. Fora disso, nada é injetado e
> você vai achar que o código está quebrado.

### 5.3 Já aproveite e marque como não indexável

Ainda em **Configurações** → **Leitura**, no fim da página, marque
**Pedir aos mecanismos de busca que não indexem este site**.

É página de demonstração com produto fictício. Você não quer que ela
ranqueie no Google competindo com o seu portfólio real.

---

## Passo 6 — Importar o template da landing

1. Abra a página criada e clique em **Editar com Elementor**
2. Espere o editor carregar (a primeira vez é lenta)
3. Na área de conteúdo, procure o ícone de **pasta** — é o botão
   **Adicionar template**. Fica junto do `+` azul, no meio da área de
   edição vazia
4. Abre a janela **Biblioteca**. Vá na aba **Meus templates**
5. Clique em **Importar template**, no alto à direita da janela
6. **Selecionar arquivo** → escolha:
   `J:\Projetos\demo-landing-infoproduto\elementor\landing-treino-30-dias.json`
7. O template aparece na lista. Clique em **Inserir**
8. Se perguntar sobre importar as configurações do documento, escolha **Sim**
9. Clique em **Atualizar**, no canto inferior esquerdo

A página inteira monta de uma vez: 10 seções, 127 elementos.

### Se as seções aparecerem sem nenhum estilo

Isto é esperado se algo deu errado em dois pontos. Na ordem:

1. Confirme que o child theme **Treino em Casa 30 Dias** está ativo em
   **Aparência** → **Temas**
2. Vá em **Elementor** → **Ferramentas** → aba **Geral** → botão
   **Regenerar arquivos e dados**. O Elementor guarda CSS em cache e às
   vezes serve o antigo

### Se aparecer sem as imagens

Normal. O template aponta para o placeholder do Elementor de propósito,
para importar sem imagem quebrada. A troca pelas imagens reais é o passo 7
do `checklist-publicacao.md`.

---

## Passo 7 — Layout de tela cheia

Sem este passo, a landing aparece **abaixo** do cabeçalho do tema, com o
nome do site e o título da página competindo com o H1. Para página de
conversão isso é ruído: nada deve disputar atenção com a promessa e o
botão.

Ainda no editor do Elementor:

- [ ] Clique no ícone de **engrenagem** no canto inferior esquerdo
      (**Configurações da página**)
- [ ] Em **Layout da página**, escolha **Elementor Tela cheia**
      (*Elementor Canvas*)
- [ ] **Atualizar**

O Canvas remove cabeçalho, rodapé e título do tema, deixando só o conteúdo
do Elementor. O botão de WhatsApp e a barra de oferta continuam
funcionando: ambos são injetados por `wp_footer`, que o Canvas mantém.

---

## O que me reportar quando terminar

Os dois pontos de risco desta montagem:

1. **Erro de PHP** ao ativar o tema. É o único arquivo que não passou por
   validação real, porque esta máquina não tem PHP instalado. Se aparecer
   tela branca ou aviso no topo do painel, me mande o texto
2. **Problema no import** do template — widget vazio, seção faltando,
   mensagem de erro do Elementor

Se os dois passarem, o padrão está validado e eu replico com segurança nos
projetos 2 e 3, em vez de arrastar o mesmo defeito três vezes.

Útil também, se quiser:

- Um print da página montada no celular e no desktop
- O resultado do Lighthouse (`F12` → aba **Lighthouse** → device
  **Mobile** → só **Performance** → **Analyze page load**)
