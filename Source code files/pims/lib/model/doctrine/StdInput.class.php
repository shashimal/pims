<?php
/*
 * Class StdInput
 * All the functions of STD inputs are handled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class StdInput extends BaseStdInput {

    /**
     * Show STD input list
     * @param $orderField
     * @param $orderBy
     * @return objects of $stdInput
     */
    public function showStdInputList() {

        try {

           $q = Doctrine_Query::create()
                    ->from('StdInput');

           $stdIputs = $q->execute();

           return $stdIputs;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Search a STD input
     * @param $searchMode
     * @param $searchValue
     * @return object of $stdInput
     */
    public function searchStdInput($searchMode, $searchValue) {

        try {

            $q = Doctrine_Query::create()
                    ->from('StdInput')
                    ->where("$searchMode = ?", $searchValue);

            $stdInput = $q->execute();

            return $stdInput;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }


    }

    /**
     * View an STD object
     * @return object of $user
     */
    public function viewStdInput() {

        try {

            $q = Doctrine_Query::create()
                    ->from('StdInput s')
                    ->where('s.input_code = ?', $this->getInputCode());

            $stdInput = $q->fetchArray();

            return $stdInput;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Save STD input object
     */

    public  function saveStdInput() {

        try {

            $this->save();

        }catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Get an object of STD input
     * @return $user
     */
    public function getStdInputObject() {

        try {

            $stdInput = Doctrine::getTable('StdInput')
                    ->find($this->getInputCode());

            return $stdInput;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get STD inputs
     * @return array of $stdInputs
     */
    public function getStdInputs() {

        $q = Doctrine_Query::create()
                ->from('StdInput ')
                ->orderBy('input_name ASC');

        $stdInputs = $q->fetchArray();

        return  $stdInputs ;
    }

    /**
     * Get STD inputs by input catgory
     * @param $inputCategoryCode
     * @return array of $stdInputs
     */
    public function getStdInputsByInputCategory($inputCategoryCode) {

        $q = Doctrine_Query::create()
                ->from('StdInput ')
                ->where('s.input_category_code = ?', $inputCategoryCode)
                ->orderBy('input_name ASC');

        $stdInputs = $q->fetchArray();

        return  $stdInputs ;
    }

    /**
     * Delete STD inputs
     */
    public function deleteStdInput($stdInputList) {

        try {

            if (is_array($stdInputList)) {

                $q = Doctrine_Query::create()
                        ->delete('StdInput')
                        ->whereIn('input_code', $stdInputList );

                $numDeleted = $q->execute();
            }
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get STD inputs and results     
     * @return array of $results
     */
    public function getInputAndResults() {

        try {

            $q = Doctrine_Query::create()
                    ->from('StdInput i')
                    ->leftJoin('i.StdInputResult r');
                    
            
            $results = $q->fetchArray();

            return $results;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }


    /**
     * Check an existing STD input
     * @return boolean
     */
    public function isStdInputExist($inputCode, $inputName) {

        try {

            $q = Doctrine_Query::create()
                    ->from('StdInput')
                    ->where("input_name = ?", $inputName);

            $stdInput = $q->fetchArray();

            if (is_array($stdInput) && !empty($stdInput)) {

                if ($stdInput) {

                    if ($stdInput[0]['input_code'] == $inputCode) {
                        return false;
                    }
                }

                return true;
            }

            return false;


        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }
}
