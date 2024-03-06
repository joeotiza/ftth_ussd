<?php

require 'vendor/autoload.php';
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Phpoffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

include 'db_connect.php';

if(isset($_POST['export_new_customers_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT * FROM `customer_details` order by reg_date desc;";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "New_Customers_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'E-Mail Address');
        $sheet->setCellValue('F1', 'RegistrationDate');
        $sheet->setCellValue('G1', 'Platform');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['Email']);
            $sheet->setCellValue('F'.$rowCount, $data['reg_date']);
            $sheet->setCellValue('G'.$rowCount, $data['source']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=new_customer_list');
        exit(0);
    }
}

if(isset($_POST['export_areas_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = $penetrationquery."ORDER BY `AreaCode`";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Area_Penetration_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'AreaCode');
        $sheet->setCellValue('B1', 'AreaName');
        $sheet->setCellValue('C1', 'LocationCode');
        $sheet->setCellValue('D1', 'Estate/Court/Road');
        $sheet->setCellValue('E1', 'Homes_Passed');
        $sheet->setCellValue('F1', 'Connected');
        $sheet->setCellValue('G1', 'Penetration');
        $sheet->setCellValue('H1', 'Active');
        $sheet->setCellValue('I1', 'Expired');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['AreaCode']);
            $sheet->setCellValue('B'.$rowCount, $data['AreaName']);
            $sheet->setCellValue('C'.$rowCount, $data['LocationCode']);
            $sheet->setCellValue('D'.$rowCount, $data['EstateName']);
            $sheet->setCellValue('E'.$rowCount, $data['homes']);
            $sheet->setCellValue('F'.$rowCount, $data['Active']+$data['Expired']);
            $sheet->setCellValue('G'.$rowCount, $data['Penetration']);
            $sheet->setCellValue('H'.$rowCount, $data['Active']);
            $sheet->setCellValue('I'.$rowCount, $data['Expired']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=new_customer_list');
        exit(0);
    }
}

if(isset($_POST['export_customers_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = $customersquery." ORDER BY `TED`";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Customers_List_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Account_ID');
        $sheet->setCellValue('B1', 'Status');
        $sheet->setCellValue('C1', 'TED');
        $sheet->setCellValue('D1', 'Current_Package');
        $sheet->setCellValue('E1', 'FirstName');
        $sheet->setCellValue('F1', 'LastName');
        $sheet->setCellValue('G1', 'MobileNumber');
        $sheet->setCellValue('H1', 'Email');
        $sheet->setCellValue('I1', 'LocationCode');
        $sheet->setCellValue('J1', 'AreaName');
        $sheet->setCellValue('K1', 'Estate/Court/Road');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Account_ID']);
            $sheet->setCellValue('B'.$rowCount, $data['Status']);
            $sheet->setCellValue('C'.$rowCount, $data['TED']);
            $sheet->setCellValue('D'.$rowCount, $data['Current_Package']);
            $sheet->setCellValue('E'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('F'.$rowCount, $data['LastName']);
            $sheet->setCellValue('G'.$rowCount, $data['MobileNumber']);
            $sheet->setCellValue('H'.$rowCount, $data['Email']);
            $sheet->setCellValue('I'.$rowCount, $data['LocationCode']);
            $sheet->setCellValue('J'.$rowCount, $data['AreaName']);
            $sheet->setCellValue('K'.$rowCount, $data['EstateName']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=customer_list');
        exit(0);
    }
}

if(isset($_POST['export_get_internet_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT *, `get_internet`.`Address` as `myAddress`, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `get_internet` INNER JOIN `customers` 
    ON `get_internet`.`Customer ID`=`customers`.`Customer ID` WHERE `Service Status` LIKE 'Active' order by `request_date` desc";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Get_Internet_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'Area');
        $sheet->setCellValue('F1', 'Address');
        $sheet->setCellValue('G1', 'Capacity');
        $sheet->setCellValue('H1', 'RequestTime');
        $sheet->setCellValue('I1', 'Platform');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['Area']);
            $sheet->setCellValue('F'.$rowCount, $data['myAddress']);
            $sheet->setCellValue('G'.$rowCount, $data['Capacity']);
            $sheet->setCellValue('H'.$rowCount, $data['request_date']);
            $sheet->setCellValue('I'.$rowCount, $data['source']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=get_internet');
        exit(0);
    }
}

if(isset($_POST['export_get_internet_new_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT * FROM `get_internet` INNER JOIN `customer_details` ON `get_internet`.`Customer ID`=`customer_details`.`Customer ID` order by `request_date` desc";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Get_Internet_new_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'Area');
        $sheet->setCellValue('F1', 'Address');
        $sheet->setCellValue('G1', 'Capacity');
        $sheet->setCellValue('H1', 'RequestTime');
        $sheet->setCellValue('I1', 'Platform');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['Area']);
            $sheet->setCellValue('F'.$rowCount, $data['Address']);
            $sheet->setCellValue('G'.$rowCount, $data['Capacity']);
            $sheet->setCellValue('H'.$rowCount, $data['request_date']);
            $sheet->setCellValue('I'.$rowCount, $data['source']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=get_internet');
        exit(0);
    }
}

if(isset($_POST['export_cases_reported_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `customers` RIGHT JOIN `cases_reported`
        ON `customers`.`Correlation ID`=`cases_reported`.`Correlation ID` WHERE `Service Status` LIKE 'Active' order by `time` desc";

    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Reported_Cases_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'EMail Address');
        $sheet->setCellValue('E1', 'Contact Number');
        $sheet->setCellValue('F1', 'Correlation ID');
        $sheet->setCellValue('G1', 'ReportedCase');
        $sheet->setCellValue('H1', 'ReportTime');
        $sheet->setCellValue('I1', 'Platform');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['EMail Address']);
            $sheet->setCellValue('E'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('F'.$rowCount, $data['Correlation ID']);
            $sheet->setCellValue('G'.$rowCount, $data['reported_case']);
            $sheet->setCellValue('H'.$rowCount, $data['time']);
            $sheet->setCellValue('I'.$rowCount, $data['source']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=cases_reported');
        exit(0);
    }
}

if(isset($_POST['export_change_plan_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `customers` RIGHT JOIN `plan_change`
    ON `customers`.`Correlation ID`=`plan_change`.`Correlation ID` WHERE `Service Status` LIKE 'Active' order by `request_time` desc";

    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Change_Plan_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'EMail Address');
        $sheet->setCellValue('E1', 'Contact Number');
        $sheet->setCellValue('F1', 'Correlation ID');
        $sheet->setCellValue('G1', 'from_mbps');
        $sheet->setCellValue('H1', 'to_mbps');
        $sheet->setCellValue('I1', 'RequestTime');
        $sheet->setCellValue('J1', 'Platform');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['EMail Address']);
            $sheet->setCellValue('E'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('F'.$rowCount, $data['Correlation ID']);
            $sheet->setCellValue('G'.$rowCount, $data['from_mbps']);
            $sheet->setCellValue('H'.$rowCount, $data['to_mbps']);
            $sheet->setCellValue('I'.$rowCount, $data['request_time']);
            $sheet->setCellValue('J'.$rowCount, $data['source']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=change_plan');
        exit(0);
    }
}

if(isset($_POST['export_chats_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `customers` RIGHT JOIN `chat`
    ON `customers`.`Customer ID`=`chat`.`Customer ID` order by `time` desc";

    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Chat_Requests_".$dt->format('Y-m-d_H-i-s');

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'EMail Address');
        $sheet->setCellValue('E1', 'Contact Number');
        $sheet->setCellValue('F1', 'Correlation ID');
        $sheet->setCellValue('G1', 'RequestTime');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['EMail Address']);
            $sheet->setCellValue('E'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('F'.$rowCount, $data['Correlation ID']);
            $sheet->setCellValue('G'.$rowCount, $data['time']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            //$writer = new Xlsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }
    else
    {
        $_SESSION['message'] = "No Records Found";
        header('Location: index.php?page=chat_requests');
        exit(0);
    }
}

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        //print_r ($data[0]);
        $ignorecolumn = 'J';//empty column without column header
        $alphabet = range('A', 'Z');
        $skip = array_search($ignorecolumn, $alphabet);

        $count = 0;
        $columns = "";
        foreach($data as $row)
        {
            $values = "";
            $index = 0;
            foreach($data[0] as $field)
            {
                if ($count > 0)
                {
                    if ($index != $skip)
                    {
                        if ($count == 1)
                        {
                            $columns .= "`". $row[$index++]."`, ";
                        }
                        else
                        {
                            $$field = addslashes($row[$index++]);
                            $values .= "'".$$field."', ";
                        }
                    }
                    else
                        $index++;
                }
            }
            
            //$columns = substr($columns, 0, -2);
            $values = substr($values, 0, -2);
            if ($count > 1)
            {
                $excelquery = "INSERT INTO `customers` (".$columns.") VALUES (".$values.");";
                try {
                    $result = mysqli_query($conn, $excelquery);
                    $success = true;
                }
            
                catch(PDOException $e) {
                //var_dump($e);
                    echo("PDO error occurred");
                }
            
                catch(Exception $e) {
                //var_dump($e);
                echo("Error occurred");
                }                
            }
            else
            {
                $excelquery = "TRUNCATE `customers`;";
                //echo $excelquery;
                $result = mysqli_query($conn, $excelquery);
                $success = true;
                $columns = substr($columns, 0, -2);
            }
            $count++;
        }
        if (isset($success))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header ('Location: index.php?page=new_customer');
        exit(0);
    }
}

if(isset($_POST['save_pyramite_active']))
{
    $fileName = $_FILES['import_pyramite_active']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_pyramite_active']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        //print_r ($data[0]);
        $count = 0;
        $columns = "";
        foreach($data as $row)
        {
            $values = "";
            $index = 0;
            foreach($data[0] as $field)
            {
                if ($count >= 0)
                {
                    if ($count == 0)
                    {
                        $columns .= "`". $row[$index++]."`, ";
                    }
                    else
                    {
                        $$field = addslashes($row[$index++]);
                        $values .= "'".$$field."', ";
                    }
                }
            }
            
            //$columns = substr($columns, 0, -2);
            $values = substr($values, 0, -2);
            if ($count > 0)
            {
                $excelquery = "INSERT INTO `pyramite_active` (".$columns.") VALUES (".$values.");";
                try {
                    $result = mysqli_query($conn, $excelquery);
                    $success = true;
                }
            
                catch(PDOException $e) {
                //var_dump($e);
                    echo("PDO error occurred");
                }
            
                catch(Exception $e) {
                //var_dump($e);
                echo("Error occurred");
                }                
            }
            else
            {
                $excelquery = "TRUNCATE `pyramite_active`;";
                //echo $excelquery;
                $result = mysqli_query($conn, $excelquery);
                $success = true;
                $columns = substr($columns, 0, -2);
            }
            $count++;
        }
        if (isset($success))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header ('Location: index.php?page=new_customer');
        exit(0);
    }
}

if(isset($_POST['save_pyramite_expired']))
{
    $fileName = $_FILES['import_pyramite_expired']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_pyramite_expired']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        //print_r ($data[0]);
        $count = 0;
        $columns = "";
        foreach($data as $row)
        {
            $values = "";
            $index = 0;
            foreach($data[0] as $field)
            {
                if ($count >= 0)
                {
                    if ($count == 0)
                    {
                        $columns .= "`". $row[$index++]."`, ";
                    }
                    else
                    {
                        $$field = addslashes($row[$index++]);
                        $values .= "'".$$field."', ";
                    }
                }
            }
            
            //$columns = substr($columns, 0, -2);
            $values = substr($values, 0, -2);
            if ($count > 0)
            {
                $excelquery = "INSERT INTO `pyramite_expired` (".$columns.") VALUES (".$values.");";
                try {
                    $result = mysqli_query($conn, $excelquery);
                    $success = true;
                }
            
                catch(PDOException $e) {
                //var_dump($e);
                    echo("PDO error occurred");
                }
            
                catch(Exception $e) {
                //var_dump($e);
                echo("Error occurred");
                }                
            }
            else
            {
                $excelquery = "TRUNCATE `pyramite_expired`;";
                //echo $excelquery;
                $result = mysqli_query($conn, $excelquery);
                $success = true;
                $columns = substr($columns, 0, -2);
            }
            $count++;
        }
        if (isset($success))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header ('Location: index.php?page=new_customer');
        exit(0);
    }
}

if(isset($_POST['save_location_codes']))
{
    $fileName = $_FILES['import_location_codes']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_location_codes']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        //print_r ($data[0]);
        $count = 0;
        $columns = "";
        foreach($data as $row)
        {
            $values = "";
            $index = 0;
            foreach($data[0] as $field)
            {
                if ($count >= 0)
                {
                    if ($count == 0)
                    {
                        $columns .= "`". $row[$index++]."`, ";
                    }
                    else
                    {
                        $$field = $row[$index++];
                        $values .= "'".$$field."', ";
                    }
                }
            }
            
            //$columns = substr($columns, 0, -2);
            $values = substr($values, 0, -2);
            if ($count > 0)
            {
                $excelquery = "INSERT INTO `location_codes` (".$columns.") VALUES (".$values.");";
                try {
                    $result = mysqli_query($conn, $excelquery);
                    $success = true;
                }
            
                catch(PDOException $e) {
                //var_dump($e);
                    echo("PDO error occurred");
                }
            
                catch(Exception $e) {
                //var_dump($e);
                echo("Error occurred");
                }                
            }
            else
            {
                $excelquery = "TRUNCATE `location_codes`;";
                //echo $excelquery;
                $result = mysqli_query($conn, $excelquery);
                $success = true;
                $columns = substr($columns, 0, -2);
            }
            $count++;
        }
        if (isset($success))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: index.php?page=customer_list');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header ('Location: index.php?page=new_customer');
        exit(0);
    }
}
?>