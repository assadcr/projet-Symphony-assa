<?php
namespace App\Service;

use App\Entity\Product;

class CartServiceFront
{

    private array $cart = [];

    public function addToCart(Product $product, int $quantity): void
    {
        if (isset($this->cart[$product->getId()])) {
            $this->cart[$product->getId()]['quantity'] += $quantity;
        } else {
            $this->cart[$product->getId()] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function getFullCart(): array
    {
        return $this->cart;
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['product']->getPrice() * $item['quantity']; // Assurez-vous que Product a une méthode getPrice()
        }
        return $total;
    }
}