<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message, .order-details {
            text-align: center;
            margin-top: 20px;
            color: #28a745;
            font-weight: bold;
        }
        .order-details {
            color: #333;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $product = htmlspecialchars($_POST['product']);
            $quantity = (int)$_POST['quantity'];
            ?>
            <h2>Order Confirmation</h2>
            <div class="order-details">
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Product:</strong> <?php echo $product; ?></p>
                <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                <p>Thank you for your order!</p>
            </div>
            <a href="" class="back-button">Place Another Order</a>
            <?php
        } else {
            ?>
            <h1>Place Your Order</h1>
            <form method="POST" action="confirm_order.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="product">Product:</label>
                <select id="product" name="product" required>
                    <option value="Protein Powder">Protein Powder</option>
                    <option value="Yoga Mat">Yoga Mat</option>
                    <option value="Dumbbells">Dumbbells</option>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>

                <button type="submit">Place Order</button>
            </form>
            <?php
        }
        ?>
    </div>

