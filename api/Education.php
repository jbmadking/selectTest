<?php

namespace Api;

class Education
{
    const EDUCATION_ARRAY = [
        "Some Schooling (disabled)\n" => 'Next step: Inadequate Experience',
        "Grade 9\n" => 'Next step: Inadequate Experience',
        "Grade 10\n" => 'Next step: Inadequate Experience',
        "Grade 11\n" => 'Next step: Dependent on Results',
        "Grade 12 / Matric (disabled)\n" => 'Dependent on Results',
        "Certificate\n" => 'Next step: Establish Level',
        "Diploma\n" => 'Next step: Longlist',
        "Degree\n" => 'Next step: Shortlist Candidate',
        "Honors\n" => 'Next step: Shortlist Candidate',
        "Professional Qualification\n" => 'Next step: Shortlist Candidate',
        "Masters\n" => 'Next step: Shortlist Candidate',
        "Doctorate\n" => 'Next step: Shortlist Candidate'
    ];

    public function get()
    {
        $education = file('./../resource/education.txt');
        array_map(function ($element) {
            return str_replace("\r\n", '', trim($element));
        }, $education);

        $this->sendResponse($education);
    }

    public function post()
    {
        $selectedItem = $_POST['selectedItem'];
        $itemList = $_POST['itemList'];
        $selectedValue = str_replace("\n", '', $selectedItem['value']);
        $selectedWeight = (int)$selectedItem['weight'] + 1;
        $action = '';

        if (isset(Education::EDUCATION_ARRAY[$selectedItem['value']])) {
            $action = Education::EDUCATION_ARRAY[$selectedItem['value']];
        }

        $response = [
            'ItemCount' => count($itemList),
            'SelectedItem' => $selectedValue,
            'SelectedItemWeight' => $selectedWeight,
            'Result' => $this->getWeightResult(count($itemList), $selectedItem['weight'], $selectedValue),
            'Action' => $action
        ];

        $this->sendResponse($response);
    }

    private function getWeightResult(int $total, int $weight, string $selected): string
    {
        $returnString = 'Your selected qualification of "' . $selected . '" ';
        $percentage = ($weight / $total) * 100;

        if ($percentage >= 61) {
            $returnString .= 'is highly desirable';
        } elseif ($percentage >= 26) {
            $returnString .= 'is dependent on additional information';
        } else {
            $returnString .= 'is inadequate for this position';
        }

        return $returnString;
    }

    private function sendResponse($response)
    {
        header('Content-type: application/json');
        print json_encode($response);
    }
}
