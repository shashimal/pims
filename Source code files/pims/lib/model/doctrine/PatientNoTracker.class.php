<?php
/*
 * Class PatientNoTracker
 * Create patinets number
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class PatientNoTracker extends BasePatientNoTracker {

    const MIN_LENGTH =4;

    /**
     * Get next auto increment ID
     * @param bool update - Update id in the database - defaults to true.
     * @return next auto increment ID
     */
    public function getNextPatientNo($year , $sex, $update = true) {

        $currentId = $this->getCurrentPatientNo($year, $sex);

        if ($update) {

            $this->updateNextPatientNo($currentId +1, $year, $sex);
        }

        $prefix = $this->_getPrefix($sex);
        $postfix = $this->_getPostfix($year);

        $nextId = $prefix .str_pad($currentId +1, self::MIN_LENGTH, "0", STR_PAD_LEFT). $postfix;

        return $nextId;

    }

    //Get the prefix for the patient no
    private function _getPrefix($sex) {

        $prefix = "";

        if($sex == "1") {

            $prefix = "M";

        }else {

            $prefix = "F";
        }

        return $prefix;
    }

    //Get the postfix for the patient no
    private function _getPostfix($year) {

        $postfix = substr($year, 2, 3);

        return $postfix;

    }

    //Get the current patient no
    public function getCurrentPatientNo($year, $sex) {

        try {

            $q = Doctrine_Query::create()
                    ->from('PatientNoTracker p')
                    ->where('p.year = ?', $year);

            $patient = $q->fetchArray();

            if(count($patient) > 0 ) {

                if($sex == "1") {

                    return $patient[0]['male'];

                }else {

                    return $patient[0]['female'];
                }
            }else {

                $this->setYear($year);
                $this->setMale(0);
                $this->setFemale(0);
                $this->save();

                return count($patient);
            }


        } catch (Exception $e) {

            throw new Exception($e->getMessage());

        }

    }

    /**
     * Update next ID
     * @param int $nextId
     */
    private function updateNextPatientNo($nextId, $year , $sex) {

        try {

            if($sex == "1") {

                $sex = "male";
            }else {
                $sex = "female";
            }

            $q = Doctrine_Query::create()
                    ->update('PatientNoTracker')
                    ->set("$sex", "'" .$nextId ."'")
                    ->where('year = ' ."'$year'");

            $q->execute();

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
}
