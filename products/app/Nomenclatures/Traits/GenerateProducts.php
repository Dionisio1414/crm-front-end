<?php

namespace App\Nomenclatures\Traits;

trait GenerateProducts
{
    public function generateProducts()
    {
        $data = $this->request;

        $preparedCharacteristicArray = [];
//        $is_base_characteristic_value = false;
//        if(isset($data['base_characteristic_value']['values'])){
//            $preparedCharacteristicArray[] = $this->formatArray($data['base_characteristic_value']['values']);
//            $is_base_characteristic_value = true;
//        }

        $is_characteristic_value = false;
        if(isset($data['characteristic_value'])){
            foreach ($data['characteristic_value'] as $values){
                $preparedCharacteristicArray[] = $this->formatArray($values['values']);
                $is_characteristic_value = true;
            }
        }

        $is_characteristic_color_value = false;
        if(isset($data['characteristic_color_value']['values'])){
            $preparedCharacteristicArray[] = $this->formatArray($data['characteristic_color_value']['values']);
            $is_characteristic_color_value = true;
        }

        $combinations = $this->get_combinations($preparedCharacteristicArray);

        $rezult = [];

        foreach ($combinations as $key=>$combinateCharacteristics){

            //Get Prices
            $rezultPrice = [];
            if(isset($data['prices']) && count($data['prices'])){
                foreach ($data['prices'] as $price){
                    $checkPrice = $this->typesPriceRepository->getItem($price['id']);
                    if(optional($checkPrice)->is_default){
                        $rezultPrice = $price;
                    }
                }

                if(!$rezultPrice){
                    $rezultPrice = $data['prices'][0];
                }
            }

            $tempProduct = [
                'name'  => $data['name'] ?? null,
                'sku'   => isset($data['sku']) ? $data['sku'] . $key : null,
                'price' => $rezultPrice,
            ];

            $countArray = count($combinateCharacteristics)-1;

//            if($is_base_characteristic_value){
//                $tempProduct['base_characteristic_value'] = (object) [
//                    'id' => $data['base_characteristic_value']['id'],
//                    'is_color' => $data['base_characteristic_value']['is_color'] ? true : false,
//                    'value_id' => $combinateCharacteristics[0]
//                ];
//            }

            if($is_characteristic_value){
                $tempCharacteristic = [];
                foreach ($data['characteristic_value'] as $key=>$characteristicValue)
                {
                    $tempCharacteristic[] = [
                        'id' => $characteristicValue['id'],
                        'value_id' => $combinateCharacteristics[$key],
                    ];
                }
                $tempProduct['characteristic_value'] = $tempCharacteristic;
            }

            if($is_characteristic_color_value){
                $tempProduct['characteristic_color_value'] = (object) [
                    'id' => $data['characteristic_color_value']['id'],
                    'value_id' => $combinateCharacteristics[$countArray],
                ];
            }

            $rezult[] = $tempProduct;
        }

        return $rezult;
    }

    public function get_combinations($arrays) {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function formatArray($data)
    {
        $temFormatArray = [];
        foreach ($data as $item)
        {
            $temFormatArray[] = $item['id'];
        }
        return $temFormatArray;
    }

}
