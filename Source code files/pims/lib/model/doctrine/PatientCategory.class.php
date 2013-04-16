<?php
/**
 * Class PatientCategory
 * All the functions of patients categories are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class PatientCategory extends BasePatientCategory {

    /**
     * Show patient category list
     * @return objects of $stdCategories
     */

    public function showPatientCategoryList() {

        try {

            $q = Doctrine_Query::create()
                     ->from('PatientCategory');

            $categories = $q->execute();

            return $categories;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Search a patient category
     * @param $searchMode
     * @param $searchValue
     * @return object of $patientCategory
     */
    public function searchPatientCategory($searchMode, $searchValue) {

        try {

            $q = Doctrine_Query::create()
                        ->from('PatientCategory')
                        ->where("$searchMode = ?", $searchValue);

            $patientCategory = $q->execute();

            return $patientCategory;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * View STD input category
     */
    public function viewPatientCategory() {

        try {

            $q = Doctrine_Query::create()
                     ->from('PatientCategory c')
                     ->where('c.category_id = ?', $this->getCategoryId());

            $patientCategory = $q->fetchArray();

            return $patientCategory;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }
    }

    /**
     * Save patient category object
     */
    public function savePatientCategory() {

        try {

            $this->save();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Get an object of STD input category
     * @return $user
     */
    public function getPatientCategoryObject() {

        try {

            $patientCategory = Doctrine::getTable('PatientCategory')
                                   ->find($this->getCategoryId());

            return  $patientCategory;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Delete STD inputs
     */
    public function deletePatientCategory($patientCategoryList) {

        try {

            if (is_array($patientCategoryList)) {

                $q = Doctrine_Query::create()
                         ->delete('PatientCategory')
                         ->whereIn('category_id', $patientCategoryList );

                $numDeleted = $q->execute();
            }
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get patient categories
     * @return array of $patientCategories
     */
    public function getPatientCategories() {

        $q = Doctrine_Query::create()
                ->from('PatientCategory')
                ->orderBy('patient_category ASC');

        $patientCategories = $q->fetchArray();

        return  $patientCategories ;
    }

    /**
     * Check already existing patient categories
     * @return boolean
     */
    public function isPatientCategoryExist($categoryCode, $categoryName) {

        try {

            $q = Doctrine_Query::create()
                    ->from('PatientCategory')
                    ->where('patient_category = ?', $categoryName);

            $patientCategory = $q->fetchArray();

            if (is_array($patientCategory) && !empty($patientCategory)) {

                if ($patientCategory) {

                    if ($patientCategory[0]['category_id'] == $categoryCode) {
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
