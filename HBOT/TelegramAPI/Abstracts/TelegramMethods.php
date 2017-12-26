<?php
namespace HBOT\TelegramAPI\Abstracts;
/**
 * Contains methods that all Telegram methods should implement
 */
abstract class TelegramMethods  {
   

    /**
     * Before making the actual request this method will be called
     *
     * It must be used to json_encode stuff, or do other changes in the internal class representation _before_ sending
     * it to the Telegram servers
     *
     * @return TelegramMethods
     */
    public function check_reply_markup()
    {
        if (!empty($this->reply_markup)) {
            $this->reply_markup = json_encode($this->reply_markup);
        }

        // Several classes may send a parse mode, so check before sending
        // TODO Do I want to validate data in here? Should I?
        /*
         * if (!empty($this->parse_mode)) {
            if (strtoupper($this->parse_mode) !== 'HTML' || strtoupper($this->parse_mode) !== 'MARKDOWN') {
                throw new InvalidParseMode(sprintf(
                    'An invalid value for parse_mode has been given. Please use HTML or Markdown. Provided: "%s"',
                    $this->parse_mode
                ));
            }
        }
         */

       // return $this;
    }

    /**
     * Exports the class to an array in order to send it to the Telegram servers without extra fields that we don't need
     *
     * @return array
     * @throws MissingMandatoryField
     */
    public function export()
    {
        $finalArray = [];
        $mandatoryFields = $this->getMandatoryFields();

        $cleanObject = new $this();
        foreach ($cleanObject as $fieldId => $value) {
			
		    //if field is empty
            if ($this->$fieldId === $cleanObject->$fieldId) {
				//and is in array of mandatoryFields
                if (in_array($fieldId, $mandatoryFields, true)) {
                    /*
					throw new MissingMandatoryField(sprintf(
                        'The field "%s" is mandatory and empty, please correct',
                        $fieldId
                    ));
					*/
					//echo "Mandatory field is empty";
                }
			//if is not empty
            } else 
			{							
				$finalArray[$fieldId] = $this->$fieldId ;				
            }
        }

        return $finalArray;
    }


}
