<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Detishop</a>

        <h3 class="px-5">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo "Welcome, " . $_SESSION['name'];
            ?>  
            <?php 
            if ($_SESSION['admin'] == 1) {
                echo "<a href=\"admin.php\" style=\"text-decoration: none; margin-left: 10px;\">Admin</a>";
            }else {
                echo "<a href=\"perfil.php\" style=\"text-decoration: none; margin-left: 10px;\">Profile</a>";
            }
            ?>

                <!-- <a href="perfil.php" style="text-decoration: none; margin-left: 10px;"><i class="fa-solid fa-user xa "></i> Profile</a> -->
                
                <a href="cart.php" style="text-decoration: none;"><i class="fa-solid fa-cart-shopping xa "></i> Cart</a>
                 <?php
                $cart_id = $_SESSION['cart_id'];
                $countp = mysqli_query($con, "SELECT SUM(quantidade) as total FROM carts WHERE cart_id = '$cart_id'");
                $data = mysqli_fetch_assoc($countp);
                $count = $data['total'];
                echo "<span id=\"cart-count\" class=\"badge bg-warning\">$count</span>";
                echo "<a href=\"logout.php\" style=\"text-decoration: none; margin-left: 10px;\">Logout</a>";

            } else {
                echo "Welcome, Guest";
                //login button
                echo "<a href=\"login.php\" style=\"text-decoration: none; margin-left: 10px;\">Login</a>";
            }
            ?>

        </h3>

    </div>
</nav>

</h3>

</div>
</nav>