<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class HoroscopeModelAnswer extends JModelList
{
    protected function getListQuery() {
        // initialize the variables
        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        
        $query  = "SELECT jv_questions.ID, jv_questions.UniqueID, jv_questions.name, jv_questions.email,jv_questions.gender,
                    jv_questions.dob, jv_questions.tob, jv_questions.pob, jv_questions.choice, jv_questions.explain_choice,";
    }
}
?>
