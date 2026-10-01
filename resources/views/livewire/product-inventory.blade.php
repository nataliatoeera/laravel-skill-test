<div class="container mt-5">
    <div class="row">
        @if (session()->has('message'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="bg-color-gray-1 text-white pl-2x pr-2x pt-1x pb-1x">
                    <div class="fs-heading-1 mb-0">{{ $isEditMode ? 'Edit Product Details' : 'Add New Product' }}</div>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="enter the product name">
                            @error('name') <span class="text-danger small mt-1 d-block fw-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">Stock</label>
                                <input type="number" class="form-control" wire:model="quantity" placeholder="0">
                                @error('quantity') <span class="text-danger small mt-1 d-block fw-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">Price ($)</label>
                                <input type="number" step="0.01" class="form-control" wire:model="price" placeholder="0.00">
                                @error('price') <span class="text-danger small mt-1 d-block fw-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn {{ $isEditMode ? 'btn-warning text-dark' : 'btn-primary' }} w-100 py-2 fw-bold mt-2 shadow-sm">
                            {{ $isEditMode ? 'Save Changes' : 'Submit' }}
                        </button>

                        @if($isEditMode)
                            <button type="button" class="btn btn-outline-secondary w-100 mt-2 py-2" wire:click="resetFields">Cancel</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-top">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fs-6">
                                @php $grandTotal = 0; @endphp
                                @forelse($products as $product)
                                    <tr>
                                        <td><span class="fw-bold text-dark">{{ $product['name'] }}</span></td>
                                        <td>
                                            <span class="badge rounded-pill px-2.5 py-1.5 {{ $product['quantity'] > 10 ? 'bg-success text-white' : ($product['quantity'] > 0 ? 'bg-warning text-dark' : 'bg-danger text-white') }}">
                                                {{ $product['quantity'] }} units
                                            </span>
                                        </td>
                                        <td><span class="fw-bold text-success">${{ number_format($product['price'], 2) }}</span></td>
                                        <td><span class="fw-bold text-success">${{ number_format(($product['price'] * $product['quantity']), 2) }}</span></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-secondary me-1 py-1" wire:click="edit({{ $product['id'] }})">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger py-1" 
                                                    onclick="confirm('Are you absolutely sure you want to drop this product record?') || event.stopImmediatePropagation()" 
                                                    wire:click="delete({{ $product['id'] }})">Delete</button>
                                        </td>
                                    </tr>

                                    @php 
                                        $grandTotal += ($product['price'] * $product['quantity']); 
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5 border-0">
                                            <p class="mb-0 fs-5 text-secondary">No data.</p>
                                        </td>
                                    </tr>
                                @endforelse

                                @if (count($products) > 0)
                                    <tr class="table-light">
                                        <th colspan="3" class="text-lg-right">GRAND TOTAL</td>
                                        <th colspan="2" class="text-lg-left">${{ number_format($grandTotal, 2) }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
