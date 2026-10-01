<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class ProductInventory extends Component
{
    public $productId, $name, $price, $quantity;
    public $isEditMode = false;
    
    private $jsonPath = 'products.json'; 

    public function mount()
    {
        if (!Storage::exists($this->jsonPath)) {
            Storage::put($this->jsonPath, json_encode([]));
        }
    }

    public function getProductsProperty()
    {
        if (!Storage::exists($this->jsonPath)) {
            Storage::put($this->jsonPath, json_encode([]));
        }

        $products = json_decode(Storage::get($this->jsonPath), true);
        usort($products, function($a, $b) {
            return $b['id'] <=> $a['id'];
        });

        return $products;
    }

    public function render()
    {
        return view('livewire.product-inventory', [
            'products' => $this->products 
        ]);
    }

    public function resetFields()
    {
        $this->name = '';
        $this->price = '';
        $this->quantity = '';
        $this->productId = null;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $products = json_decode(Storage::get($this->jsonPath), true);

        $products[] = [
            'id' => time(), 
            'name' => $this->name,
            'quantity' => (int) $this->quantity,
            'price' => number_format((float)$this->price, 2, '.', ''),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        Storage::put($this->jsonPath, json_encode($products, JSON_PRETTY_PRINT));
        session()->flash('message', 'Product added!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $products = json_decode(Storage::get($this->jsonPath), true);
        
        foreach ($products as $product) {
            if ($product['id'] == $id) {
                $this->productId = $product['id'];
                $this->name = $product['name'];
                $this->quantity = $product['quantity'];
                $this->price = $product['price'];
                $this->isEditMode = true;
                break;
            }
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $products = json_decode(Storage::get($this->jsonPath), true);

        foreach ($products as &$product) {
            if ($product['id'] == $this->productId) {
                $product['name'] = $this->name;
                $product['quantity'] = (int) $this->quantity;
                $product['price'] = number_format((float)$this->price, 2, '.', '');
                $product['updated_at'] = now()->toDateTimeString();
                break;
            }
        }

        Storage::put($this->jsonPath, json_encode($products, JSON_PRETTY_PRINT));
        session()->flash('message', 'Product updated!');
        $this->resetFields();
    }

    public function delete($id)
    {
        $products = json_decode(Storage::get($this->jsonPath), true);

        $products = array_filter($products, function ($product) use ($id) {
            return $product['id'] != $id;
        });

        Storage::put($this->jsonPath, json_encode(array_values($products), JSON_PRETTY_PRINT));
        session()->flash('message', 'Product removed!');
    }
}
