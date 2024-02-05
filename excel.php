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

    $fileName = "New_Customers";

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'RegistrationDate');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['reg_date']);
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

if(isset($_POST['export_get_internet_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $exportquery = "SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `get_internet` INNER JOIN `customers` ON `get_internet`.`Customer ID`=`customers`.`Customer ID` order by `request_date` desc";
    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Get_Internet";

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'Location');
        $sheet->setCellValue('F1', 'Capacity');
        $sheet->setCellValue('G1', 'RequestTime');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['Location']);
            $sheet->setCellValue('F'.$rowCount, $data['Capacity']);
            $sheet->setCellValue('G'.$rowCount, $data['request_date']);
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

    $fileName = "Get_Internet_new";

    if (mysqli_num_rows($myresult) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'FirstName');
        $sheet->setCellValue('C1', 'LastName');
        $sheet->setCellValue('D1', 'Contact Number');
        $sheet->setCellValue('E1', 'Location');
        $sheet->setCellValue('F1', 'Capacity');
        $sheet->setCellValue('G1', 'RequestTime');

        $rowCount = 2;
        foreach($myresult as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['Customer ID']);
            $sheet->setCellValue('B'.$rowCount, $data['FirstName']);
            $sheet->setCellValue('C'.$rowCount, $data['LastName']);
            $sheet->setCellValue('D'.$rowCount, $data['Contact Number']);
            $sheet->setCellValue('E'.$rowCount, $data['Location']);
            $sheet->setCellValue('F'.$rowCount, $data['Capacity']);
            $sheet->setCellValue('G'.$rowCount, $data['request_date']);
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
        ON `customers`.`Correlation ID`=`cases_reported`.`Correlation ID` order by `time` desc";

    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Reported_Cases";

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
    ON `customers`.`Correlation ID`=`plan_change`.`Correlation ID` order by `request_time` desc";

    $myresult = mysqli_query($conn, $exportquery);

    $fileName = "Change_Plan";

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

    $fileName = "Chat_Requests";

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
                    if ($count == 1)
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
            if ($count > 1)
            {
                
                $excelquery = "INSERT INTO `customers` (".$columns.") VALUES (".$values.");";
                //echo $excelquery;
                $result = mysqli_query($conn, $excelquery);
                $success = true;
                
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
?>