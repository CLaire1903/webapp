<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <div id="storeLogo">
            <img src="image/logo/logoW.png">
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navContent rounded-pill col-12 col-lg-10">
            <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="home" class="nav-link word" aria-current="page" href="index.php">Home</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="product" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="createProduct" class="dropdown-item word" href="product.php">Create Product</a></li>
                                <li><a id="productList" class="dropdown-item word" href="product_list.php">Product List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="customer" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Customer
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="createCus" class="dropdown-item word" href="customer.php">Create Customer</a></li>
                                <li><a id="cusList" class="dropdown-item word" href="customer_list.php">Customer List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="order" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="createOrder" class="dropdown-item word" href="order.php">Create Order</a></li>
                                <li><a id="orderList" class="dropdown-item word" href="order_list.php">Order List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="contact" class="nav-link word" href="contact.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="logout" class="nav-link word" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>