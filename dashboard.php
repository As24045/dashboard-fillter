<?php
require_once("./inc-common/head.php");
require_once("./inc-common/header.php");
require_once("./inc-common/sidebar.php");

if (in_array("Expense", $roleview) || in_array("All", $roleview)) {
} else {
    echo "<script>window.location.assign('./404.php')</script>";
    exit();
}

$selected_month = isset($_GET['f_month']) ? $_GET['f_month'] : date('Y-m');
$current_month = date('Y-m');

$income_table = ($selected_month == $current_month) ? "tbl_bank" : "tbl_monthly_closeup";

$date_where = " WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month' ";

function fetchSum($action, $table, $column, $where_clause, $category = null)
{
    $sql = "SELECT SUM($column) as 'val' FROM `$table` $where_clause";
    if ($category) {
        $sql .= " AND category LIKE '$category'";
    }
    $res = $action->db->sql($sql);
    return (@$res[0]['val'] > 0) ? @$res[0]['val'] : 0;
}
?>

<style>
    th {
        white-space: nowrap;
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.3s;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .avatar-icon i {
        font-size: 24px;
        color: rgba(255, 255, 255, 0.8);
    }

    .bg-t {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-title {
        position: relative;
        padding-left: 15px;
        margin-bottom: 25px;
        font-weight: 700;
        color: #333;
    }

    .section-title::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        border-radius: 2px;
    }

    .title-income::before {
        background-color: #36b9cc;
    }

    .title-expense::before {
        background-color: #e74a3b;
    }

    .h6_new {
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="header-sub-title">
            <nav class="breadcrumb breadcrumb-dash">
                <a href="./" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Finance</a>
                <span class="breadcrumb-item active">Dashboard</span>
            </nav>
        </div>
    </div>

    <!-- ================= SECTION 1: INCOME STATISTICS ================= -->
    <div class="d-flex justify-content-between w-100">
        <h4 class="section-title title-income">
            Income Statistics (<?php echo date('M Y', strtotime($selected_month)); ?>)
        </h4>
        <div class="d-flex justify-content-between">
            <?php if ($selected_month == $current_month): ?>
                <form id="closeup" class="mr-2">
                    <button type="submit" class="btn btn-primary">Closeup</button>
                </form>
            <?php endif; ?>

            <div class="form-group ml-2 mb-0" style="min-width: 150px;">
                <form method="GET" id="monthFilterForm">
                    <input type="month" name="f_month" class="form-control" value="<?php echo $selected_month; ?>" onchange="this.form.submit()">
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 1. Team -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #62dcf7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'team', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Team</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 2. Operations -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #f6c23e;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'opration', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Operations</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-cogs"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 3. Marketing -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #36b9cc;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'market', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Marketing</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-bullhorn"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 4. Miscellaneous -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #858796;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'mics', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Miscellaneous</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-th-large"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 5. Finance -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #1cc88a;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'finances', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Finance</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-wallet"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 6. Travel -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #fd7e14;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'travel', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Travel & Food</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-utensils"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 7. Profit -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'profit', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Profit</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 8. Growth -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #c7bbda;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, $income_table, 'growth', $date_where); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Growth</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-rocket"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="m-v-30">

    <!-- ================= SECTION 2: EXPENSE (Source: tbl_expense) ================= -->
    <h4 class="section-title title-expense">Expense Statistics (<?php echo date('M Y', strtotime($selected_month)); ?>)</h4>
    <div class="row">
        <!-- 1. Team Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #62dcf7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Team'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Team Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 2. Operations Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #f6c23e;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Operations'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Ops Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-cogs"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 3. Marketing Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #36b9cc;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Marketing'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Market Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-bullhorn"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 4. Miscellaneous Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #858796;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Miscellaneous'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Misc Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-th-large"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 5. Finance Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #1cc88a;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Finance'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Finance Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-wallet"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 6. Travel Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #fd7e14;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Travel'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Travel Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-utensils"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 7. Profit Section -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Profit'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Profit Share</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 8. Growth Expense -->
        <div class="col-md-3 col-6">
            <div class="card" style="background-color: #c7bbda;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-b-0 h6_new text-white"><?php echo fetchSum($action, 'tbl_expense', 'amount', $date_where, 'Growth'); ?></h6>
                            <p class="m-b-0 text-white font-12 opacity-8">Growth Exp.</p>
                        </div>
                        <div class="avatar-icon bg-t"><i class="fas fa-rocket"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once("./inc-common/footer.php");
require_once('./inc-common/script.php');
?>