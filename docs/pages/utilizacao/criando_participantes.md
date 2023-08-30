# Testando a criação de participantes

> Para este tutorial é necessário saber como o Plugin funciona, saiba mais [clicando aqui](/pages/primeiros_passos/como_funciona)

> <strong>Observação</strong>: A utilização do plugin do WooCommerce, só é possível com o campo CPF definido como não obrigatório no superadmin da organização.

### Efetuando um pedido no WooCommerce

1. O primeiro passo para simular um pedido, é acessar a aba <strong>WooCommerce</strong>, no painel de administrador do seu site WordPress. Após acessar, selecione a opção <strong>Pedidos</strong> e adicione um pedido.

<figure class="thumbnails">
  <img src="_media/add_shop_order.png" alt="Screenshot of coverpage" title="Adicionando um pedido">
</figure>

<br/>

2. O segundo passo é informar os detalhes do pedido, é necessário informar um <strong>Cliente</strong> e um <strong>Produto</strong>. O cliente somente será cadastrado no curso (produto), se o status do pagamento for igual a "concluído".

<figure class="thumbnails">
  <img src="_media/add_shop_order_infos.png" alt="Screenshot of coverpage" title="Adicionando um pedido">
</figure>

<br/>

<figure class="thumbnails">
  <img src="_media/add_shop_order_product.png" alt="Screenshot of coverpage" title="Adicionando um pedido">
</figure>

<br/>

### Recebendo o cliente no Twygo

Após o pedido ser efetudo no WooCommerce com o status "concluído", o cliente será enviado ao Twygo, cadastrado no curso adquirido e na sua organização.

<figure class="thumbnails">
  <img src="_media/list_user.png" alt="Screenshot of coverpage" title="Adicionando um pedido">
</figure>

<br/>

<figure class="thumbnails">
  <img src="_media/list_user_org.png" alt="Screenshot of coverpage" title="Adicionando um pedido">
</figure>

<br/>

<strong style="font-size: 12px;">Obs: Você poderá editar o curso no Twygo para exigir a confirmação da inscrição. Essa ação resultará no cadastro dos clientes como "pendentes" no curso adquirido pelo WooCommerce.</strong>
