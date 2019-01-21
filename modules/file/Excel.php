<?php
/**
 * Description of Excel
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 05, 2016 01:31
 */
class Excel {
    public static function export($data, $titles, $fileName, $pageTitle, $tabName, $isLastColumnBold=false) {
        
        $objPHPExcel = new PHPExcel();
        
        $allBorderStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FFC0C0C0')
                ),
            )
        );
        
        /*## White Background whole over the sheet
        $objPHPExcel->getDefaultStyle()->applyFromArray(
            array(
                'fill' => array(
                    'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('argb' => 'FFFFFFFF')
                ),
            )
        );*/
        
        ## Create First Worksheet
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($tabName);
        
        $totColumns = count($titles);
        $lastColumn = self::getColumn($totColumns);
        
        
        ## Set TITLE            
        $sheet->setCellValue('A1', $pageTitle);
        $sheet->mergeCells('A1:'.$lastColumn.'1');
        $sheet->getStyle('A1')->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
        /*
        $sheet->getStyle('A1:'.$lastColumn.'1')->getFont()
                                    ->setSize(16)               // set fontsize
                                    ->setBold(true)             // set bold
                                    ->setItalic(true)         // set italic
                                    ;
        */
        $styleArray = array(
                        'font'  => array(
                            'bold'  => true,
                            'color' => array('rgb' => '1F6F43'),
                            'size'  => 15,
                            //'name'  => 'Verdana'
                        )
                    );
        $sheet->getStyle('A1:'.$lastColumn.'1')->applyFromArray($styleArray);
        
        $i=3; $j=1;
        foreach($titles as $ttl) {
           
            $col = self::getColumn($j);
            $sheet->setCellValue($col.$i, $ttl);
            $j++;
        }
        $sheet->getStyle('A'.$i.':'.$lastColumn.$i)->getFont()->setBold(true);
        $sheet->getStyle('A'.$i.':'.$lastColumn.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$i.':'.$lastColumn.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFAAAAAA');
        
        $i++;
        
        foreach($data as $dt) {
            $j= 1;
            //Zend_Debug::dump($dt); die;
            foreach($dt as $d) {
                $col = self::getColumn($j);
                //$sheet->setCellValue($col.$i, $d);
                $sheet->getCell($col.$i)->setValueExplicit($d, PHPExcel_Cell_DataType::TYPE_STRING);
                $j++;
            }
            
           $i++;
        }
        
        ## Set Border
       $sheet->getStyle('A3:'.$lastColumn.($i-1))->applyFromArray($allBorderStyle);

        ## Set automatic width
        for($j=1; $j<=$totColumns; $j++) {
            $col = self::getColumn($j);
            $sheet->getColumnDimension($col)->setAutoSize(true);
       }
       
       if($isLastColumnBold) {
            $sheet->getStyle('A'.($i-1).':'.$lastColumn.($i-1))->getFont()->setBold(true);
       }
       
        ## Active First Sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //////////////////////////////////////////////////////////

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
    
    public static function getColumn($num) {
        $col = '';

        $first 	= floor(($num-1)/26);
        $second = $num - $first*26;

        if(!empty($first)) {
            $col .= chr($first+64);
        }

        if(!empty($second)) {
            $col .= chr($second+64);
        }

        return $col;
    }
}
