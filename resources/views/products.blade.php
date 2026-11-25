<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
</head>
<body>
    <header>
        <h1>{{$title}}</h1>
    </header>

    <main>
        @if (session('success'))
            <p style="color: green">
                {{session('success')}}</p>
        @endif
        <section>
            <h2>Add Product</h2>
            <form action="{{ $product ? '/products/'.$product->id : '/products' }}" method="post">
                @csrf
                @if ($product)
                    @method('PUT')
                @endif
                @foreach ($errors->all() as $error)
                    <p style="color: red">{{ $error}}</p>
                @endforeach
                <input type="text" name="name" id="name" placeholder="Product Name" value="{{ $product->name??'' }}">
                <input type="text" name="brand" id="brand" placeholder="Product Brand" value="{{ $product->brand ??'' }}">
                <input type="number" name="price" id="price" placeholder="Product Price" value="{{ $product->price??'' }}">
                <input type="number" name="quantity" id="quantity" placeholder="Product Quantity" value="{{ $product->quantity??'' }}">
                <button type="submit">
                    {{ $product? 'Update Product':'Save Product' }}
                </button>
            </form>

            <h2>Import Products</h2>
            <form action="/products/import" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv,.ods">
            <button type="submit">Import products</button>
            </form>
        </section>

        <section>
            <h2>Products List</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->count()>0)
                        @foreach ($products as $product)
                        <tr>
                            <td>{{$products->firstItem()+$loop->index}}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->brand}}</td>
                            <td>{{$product->price}}</td>
                            <td>{{$product->quantity}}</td>
                            <td>
                                <a href="/{{ $product->id }}">Edit</a>
                                <form onsubmit="return confirm('Are you sure you want to delete this product?')" action="/products/{{ $product->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                        <td colspan="100%">No Products found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            {{ $products->links() }}
        </section>
    </main>

    <footer>
        <p>&copy; {{date('Y')}} Electronics CRUD System</p>
    </footer>
</body>
</html>