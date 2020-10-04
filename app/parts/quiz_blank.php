<?php
    
    $sheet = $spreadsheet->getSheet(0);
                
    $num_offset = 0;
    $sheet->setTitle('Бланки');

    $i = 0;
    while ($i++<($variant - 1)) {

        $sheet->setCellValue('A'.($num_offset + $i), 'Тема №');
        $sheet->getStyle('A'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('B'.($num_offset + $i), $row_topic['t_number']);


        $sheet->setCellValue('C'.($num_offset + $i), 'гр. №');
        $sheet->getStyle('C'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->getStyle('C'.($num_offset + $i))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('E'.($num_offset + $i), '1');
        $sheet->getStyle('E'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('F'.($num_offset + $i), '2');
        $sheet->getStyle('F'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('G'.($num_offset + $i), '1');
        $sheet->getStyle('G'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('H'.($num_offset + $i), '2');
        $sheet->getStyle('H'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('I'.($num_offset + $i), '1');
        $sheet->getStyle('I'.($num_offset + $i))->getFont()->setBold(true);

        $sheet->setCellValue('J'.($num_offset + $i), 'v.'.$version_id);
        $sheet->getStyle('J'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->mergeCells('J'.($num_offset + $i).':J'.($num_offset + 3 + $i));

        $sheet->setCellValue('A'.($num_offset + 1 + $i), 'Ф.И.О. (name)');
        $sheet->getStyle('A'.($num_offset + 1 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('A'.($num_offset + 1 + $i).':B'.($num_offset + 1 + $i));

        $sheet->mergeCells('C'.($num_offset + 1 + $i).':D'.($num_offset + 1 + $i));

        $sheet->setCellValue('A'.($num_offset + 2 + $i), 'Дата (date)');
        $sheet->getStyle('A'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('A'.($num_offset + 2 + $i).':B'.($num_offset + 2 + $i));

        $sheet->mergeCells('C'.($num_offset + 2 + $i).':D'.($num_offset + 2 + $i));

        $sheet->setCellValue('E'.($num_offset + 2 + $i), '6');
        $sheet->getStyle('E'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('F'.($num_offset + 2 + $i), '7');
        $sheet->getStyle('F'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('G'.($num_offset + 2 + $i), '8');
        $sheet->getStyle('G'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('H'.($num_offset + 2 + $i), '9');
        $sheet->getStyle('H'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('I'.($num_offset + 2 + $i), '10');
        $sheet->getStyle('I'.($num_offset + 2 + $i))->getFont()->setBold(true);

        $sheet->setCellValue('A'.($num_offset + 3 + $i), 'Вариант '.$i);
        $sheet->getStyle('A'.($num_offset + 3 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('A'.($num_offset + 3 + $i).':B'.($num_offset + 3 + $i));

        $sheet->setCellValue('C'.($num_offset + 3 + $i), $row_topic['c_short_name']);
        $sheet->mergeCells('C'.($num_offset + 3 + $i).':D'.($num_offset + 3 + $i));

        $sheet->getStyle('A'.($num_offset + $i).':J'.($num_offset + 3 + $i))->applyFromArray($styleArray);



        // Second column
        $sheet->setCellValue('L'.($num_offset + $i), 'Тема №');
        $sheet->getStyle('L'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('M'.($num_offset + $i), $row_topic['t_number']);


        $sheet->setCellValue('N'.($num_offset + $i), 'гр. №');
        $sheet->getStyle('N'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->getStyle('N'.($num_offset + $i))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue('P'.($num_offset + $i), '1');
        $sheet->getStyle('P'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('Q'.($num_offset + $i), '2');
        $sheet->getStyle('Q'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('R'.($num_offset + $i), '1');
        $sheet->getStyle('R'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('S'.($num_offset + $i), '2');
        $sheet->getStyle('S'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->setCellValue('T'.($num_offset + $i), '1');
        $sheet->getStyle('T'.($num_offset + $i))->getFont()->setBold(true);

        $sheet->setCellValue('U'.($num_offset + $i), 'v.'.$version_id);
        $sheet->getStyle('U'.($num_offset + $i))->getFont()->setBold(true);
        $sheet->mergeCells('U'.($num_offset + $i).':U'.($num_offset + 3 + $i));

        $sheet->setCellValue('L'.($num_offset + 1 + $i), 'Ф.И.О. (name)');
        $sheet->getStyle('L'.($num_offset + 1 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('L'.($num_offset + 1 + $i).':M'.($num_offset + 1 + $i));

        $sheet->mergeCells('N'.($num_offset + 1 + $i).':O'.($num_offset + 1 + $i));

        $sheet->setCellValue('L'.($num_offset + 2 + $i), 'Дата (date)');
        $sheet->getStyle('L'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('L'.($num_offset + 2 + $i).':M'.($num_offset + 2 + $i));

        $sheet->mergeCells('N'.($num_offset + 2 + $i).':O'.($num_offset + 2 + $i));

        $sheet->setCellValue('P'.($num_offset + 2 + $i), '6');
        $sheet->getStyle('P'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('Q'.($num_offset + 2 + $i), '7');
        $sheet->getStyle('Q'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('R'.($num_offset + 2 + $i), '8');
        $sheet->getStyle('R'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('S'.($num_offset + 2 + $i), '9');
        $sheet->getStyle('S'.($num_offset + 2 + $i))->getFont()->setBold(true);
        $sheet->setCellValue('T'.($num_offset + 2 + $i), '10');
        $sheet->getStyle('T'.($num_offset + 2 + $i))->getFont()->setBold(true);

        $sheet->setCellValue('L'.($num_offset + 3 + $i), 'Вариант '.$i);
        $sheet->getStyle('L'.($num_offset + 3 + $i))->getFont()->setBold(true);
        $sheet->mergeCells('L'.($num_offset + 3 + $i).':M'.($num_offset + 3 + $i));

        $sheet->setCellValue('N'.($num_offset + 3 + $i), $row_topic['c_short_name']);
        $sheet->mergeCells('N'.($num_offset + 3 + $i).':O'.($num_offset + 3 + $i));

        $sheet->getStyle('L'.($num_offset + $i).':U'.($num_offset + 3 + $i))->applyFromArray($styleArray);

        $num_offset += 4;

    }


    $sheet->getStyle('B1:U'.($num_offset + 10))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);



    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setWidth(5);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setWidth(5);

    $sheet->getColumnDimension('E')->setWidth(5);
    $sheet->getColumnDimension('F')->setWidth(5);
    $sheet->getColumnDimension('G')->setWidth(5);
    $sheet->getColumnDimension('H')->setWidth(5);
    $sheet->getColumnDimension('I')->setWidth(5);

    $sheet->getColumnDimension('J')->setAutoSize(true);

    $sheet->getColumnDimension('K')->setWidth(5);

    $sheet->getColumnDimension('L')->setAutoSize(true);
    $sheet->getColumnDimension('M')->setWidth(5);
    $sheet->getColumnDimension('N')->setAutoSize(true);
    $sheet->getColumnDimension('O')->setWidth(5);

    $sheet->getColumnDimension('P')->setWidth(5);
    $sheet->getColumnDimension('Q')->setWidth(5);
    $sheet->getColumnDimension('R')->setWidth(5);
    $sheet->getColumnDimension('S')->setWidth(5);
    $sheet->getColumnDimension('T')->setWidth(5);

    $sheet->getColumnDimension('U')->setAutoSize(true);

    $sheet->getDefaultRowDimension()->setRowHeight(25);

    $sheet->getStyle('A1:U100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

    $sheet->getPageSetup()->setPrintArea('A1:U'.($num_offset + 4));
    $sheet->getPageSetup()->setScale(85);
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);