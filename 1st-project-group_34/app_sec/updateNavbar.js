// updateNavbar.js
function updateNavbar() {
    // Faça uma solicitação AJAX para obter a quantidade de itens no carrinho do banco de dados
    fetch('quantidade.php') // Substitua 'obter_quantidade_carrinho.php' pelo nome do arquivo que obtém a quantidade do carrinho
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartItemCount = data.quantity; // A quantidade de itens no carrinho

                // Atualize o contador do carrinho na barra de navegação
                document.getElementById('cart-count').textContent = cartItemCount;
            } else {
                console.error('Erro ao obter a quantidade do carrinho.');
            }
        })
        .catch(error => {
            console.error('Erro na solicitação AJAX:', error);
        });
}

// Chame a função para atualizar o contador do carrinho assim que a página for carregada
updateNavbar();
