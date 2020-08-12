# Como functiona?

> O Plugin envia dados para o Twygo, à partir de algumas ações feitas no WooCommerce.

### 1. Criação de Produtos

Ao cadastrar um produto na sua loja WooCommerce, ele é automáticamente criado no Twygo.
O cadastro automático do produto na plataforma, somete será efetuada se o seu curso possuir a tag informada nas [configurações](/pages/configuracoes/configuracoes.md) do plugin.

Se o produto for salvo como rascunho, será criado um curso "Em desenvolvimento". Caso seja publicado no WooCommerce, ele será liberado no Twygo (o que permite ser executado pelos usuários).

##### Obs: Toda vez que o produto for atualizado no WooCommerce (nome, descrição e preço) ele será atualziado no Twygo.



### 2. Compra de produtos por um cliente

Quando um pedido for concluído com sucesso, o plugin cadastrará automáticamente o cliente no curso (Produto) que foi adquirido na compra.

##### Obs: O curso precisa ter sido cadastrado previamente no WooCommerce e ter sido enviado para o Twygo. Caso contrário deverá ser mapeado uma equivalência de Produto WooCommerce com Curso Twygo já existênte, passa funcionar está integração.
