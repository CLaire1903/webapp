<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <h3 class="text-light">Claire_Store</h3>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="home" class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="product" class="nav-link dropdown-toggle navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a id="createProduct" class=" dropdown-item" href="product.php">Create Product</a></li>
                            <li><a id="productList" class=" dropdown-item" href="product_list.php">Product List</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="customer" class="nav-link dropdown-toggle navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customer
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a id="createCus" class="dropdown-item" href="customer.php">Create Customer</a></li>
                            <li><a id="cusList" class="dropdown-item" href="customer_list.php">Customer List</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="order" class="nav-link dropdown-toggle navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Order
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a id="createOrder" class="dropdown-item" href="order.php">Create Order</a></li>
                            <li><a id="orderList" class="dropdown-item" href="order_list.php">Order List</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="contact" class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="logout" class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
</nav>