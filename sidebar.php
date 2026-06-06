<div class="sidebar">

    <div class="logo">

        <i class="fa-solid fa-building"></i>

        <span>VendorBridge</span>

    </div>

    <ul>

        <li class="<?= ($page=='dashboard') ? 'active' : '' ?>">

            <a href="dashboard.php">

                <i class="fa-solid fa-chart-line"></i>

                Dashboard

            </a>

        </li>

        <li class="<?= ($page=='vendors') ? 'active' : '' ?>">

            <a href="vendors.php">

                <i class="fa-solid fa-users"></i>

                Vendors

            </a>

        </li>

        <li class="<?= ($page=='rfq') ? 'active' : '' ?>">

            <a href="rfq.php">

                <i class="fa-solid fa-file-contract"></i>

                RFQs

            </a>

        </li>

        <li class="<?= ($page=='quotation') ? 'active' : '' ?>">

            <a href="quotations.php">

                <i class="fa-solid fa-scale-balanced"></i>

                Quotations

            </a>

        </li>

        <li class="<?= ($page=='approval') ? 'active' : '' ?>">

            <a href="approvals.php">

                <i class="fa-solid fa-circle-check"></i>

                Approvals

            </a>

        </li>

        <li class="<?= ($page=='po') ? 'active' : '' ?>">

            <a href="purchase_orders.php">

                <i class="fa-solid fa-cart-shopping"></i>

                Purchase Orders

            </a>

        </li>


        <li class="<?= ($page=='reports') ? 'active' : '' ?>">

            <a href="reports.php">

                <i class="fa-solid fa-chart-pie"></i>

                Reports

            </a>

        </li>

        <li>

            <a href="login.php">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </li>

    </ul>

</div>