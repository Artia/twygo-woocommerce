# Como functiona a integração?

> A integraço envia dados para o Twygo, à partir de algumas ações feitas no WooCommerce.

<br/>

#### 1. Criação de Produtos

Ao criar um produto na sua loja WooCommerce, ele é automáticamente cadastrado dentro da plataforma Twygo.
O cadastro automático do produto na plataforma, somete será efetuada se o seu curso possuir a tag informada nas configurações do plugin.

Toda vez que o produto for atualizado no WooCommerce (nome, descrição e preço), ele será atualziado no Twygo.

Se o produto for salvo como rascunho, será criado um curso "Em desenvolvimento". Caso seja publicado no WooCommerce, ele será liberado no Twygo.

<br/>

#### 2. Compra de produtos por um cliente

Quando um pedido for concluído com sucesso, o plugin cadastrará automáticamente o cliente no curso (Produto) que foi adquirido na compra.

###### Obs: O curso precisa ter sido cadastrado previamente no WooCommerce e ter sido enviado para o Twygo. Caso contrário deverá ser mapeado uma equivalência de Produto WooCommerce com Curso Twygo já existênte, passa funcionar está integração.
