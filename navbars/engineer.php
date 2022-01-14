<!-- ИНЖЕНЕР -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-md">
            <a class="navbar-brand" href="mainpage.php"><img src="../assets/pictures/icon/icon.ico" alt="" width="24" height="24" class="d-inline-block align-text-top">  Главная</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="employee.php">Сотрудники</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vendor.php">Поставщики</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="service.php">Оказание услуг</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="reports.php">Бухгалтерия</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="waste_list.php">Отходы</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Учет материалов
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="orders.php">Приход</a></li>
                        <li><a class="dropdown-item" href="expenses.php">Расход</a></li>
                        <li><a class="dropdown-item" href="stock-balances.php">Материалы в наличии</a></li>
                    </ul>
                </li>
            </ul>
            </div>
            <div class="d-flex">
                <form action="php/logout.php">
                    <button class="btn btn-danger">Выйти</button>
                </form>
            </div>
        </div>
    </nav>
