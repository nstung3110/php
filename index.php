<!DOCTYPE html>
<html>
<head>
    <title> Danh Sách Sinh Viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<meta charset="UTF-8">
<a href="nhapthongtin.php"> Thêm Sinh Viên</a>
<style>

    table {
       border-collapse: collapse;
       width: 100%;
       margin: 0px;

   }

   table th, td {
    border: 1px solid black ;
    text-align: center;
    padding: 10px;

}

th {
    background-color: rgb(86, 243, 71);
}

img {
    max-height: 150px;
    max-width: 150px;
}

h2 {
    text-align: center;
}

li {
    float: left;
    display: block;
    margin: 10px;
    font-size: 20px;


}
li a {

    color: #666;
    text-align: center;
    padding: 10px;
    text-decoration: none;
}

.page-link:hover {
    color: black;
}

.page-link:active {
    color: red;
}

</style>
<body>

    <?php
    include 'connect.php';


function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {

        foreach ($array as $k => $v) {

            if (is_array($v)) {

                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
            asort($sortable_array);
            break;
            case SORT_DESC:
            arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

$sql= "SELECT * FROM students";
$result = $conn->query($sql);
$rows = $result-> fetch_all(MYSQLI_ASSOC);

   if (isset($_GET["sapxep"])){ // Nếu có sắp xếp thì sắp xếp.
       $sapxep = $_GET["sapxep"]; 
   }
   if ($sapxep == "fullname") { // sắp xếp fullnbame thì sắp xếp fullname.
        $rows = array_sort($rows,'fullname', SORT_ASC);
    }elseif($sapxep == "birthday") { // xắp sếp birdday thì...
        $rows = array_sort($rows,'birthday', SORT_ASC);
    }
    
    // kiểm tra 
    if (isset($_GET["page"])){
        $page = $_GET["page"];
    } else{
        echo $page =1;
    }
    //phân trang
    $records = 5;
    //khai bao bien de chua tong so trang
     
    $tongsodong = count($rows);
    $total_pages =1;
    //tính tổng sổ trang
    if($tongsodong%$records==0){
    $total_pages = ceil($tongsodong/$records);

    }else {
        $total_pages = (int)($tongsodong/$records)+1;
    }
    //bien the hien dong bat dau va trang hien tai
    $dongbatdau =1;
    $pagenow = 1;
    //tinh trang hien tai laf trang nao vaf bat dau cho du lieu trong csdl tu dong nao.
    //neu trang hien tai la 1 thi se bat dau chon tu dong 1
    if((!isset($_GET['pagenow]'))|| ($_GET['pagenow']=='1'))
    {
        $dongbatdau = 0;
        $pagenow = 1;
    } else {
        //lua chon dong bat dau hien thi va lay ve trang hien tai
        $dongbatdau = ($_GET['pagenow']-1)*$records;
        $pagenow = $_GET['pagenow'];
    }

    if ($result->num_rows > 0) {
        echo '<div>';
        echo '<a href ="index.php?sapxep=fullname"> Sort by fullname </a>';
        echo '<a href ="index.php?sapxep=birthday"> Sort by birthday </a>';
        echo '</div>';

            echo "<h2> Danh Sách Sinh Viên </h2>";
            echo '<table>';
            echo '<tr>';
            echo '<th> Họ Tên </th>';
            echo '<th> Năm Sinh </th>';
            echo '<th> Email </th>';
            echo '<th> Số điện thoại </th>';
            echo '<th> Ảnh đại diện </th>';
            echo '</tr>';

            foreach ($rows as $row) {

             echo "<tr>";
             echo '<td>' .$row["fullname"].'</td>';
             $date = getdate();
             echo '<td>' .$row["birthday"].'</td>';
             echo '<td>' .$row["email"].'</td>';
             echo '<td>' .$row["phone"].'</td>';
             echo '<td><img src= '.$row["avatar"].'></td>';
             echo "</tr>";	
         }
         echo "</table>";
    }

     ?>
     <ul class="pagination">
        <li>
            <a  href="<?php if($page <= 1){ echo '#';

              }else{echo "?page=".($page +1); }?>">
                <span aria-hidden="true">&laquo;</span>
                <span>Previous</span>
            </a>
        </li>

        <li><a href="'?sapxep='.$sapxep.&page=1">1</a></li>

        <li>
            <a  href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>"><span aria-hidden="true">&raquo;</span>
                <span>Next</span>
            </a>
        </li>

        <li>
            <a  href="?page=<?php echo $total_pages; 
            ?>"><span aria-hidden="true">&raquo;</span>
            <span>Last</span>
        </a>
    </li >

</ul>
</body>
</html>