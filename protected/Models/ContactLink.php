<?php


class ContactLink extends AppData {

	protected $table = 'contact_link';

		
	/* 
	 * Fetch all the prospect/resident contacts
	 *
	 */
	public function fetchContacts($prospectId) {
		// need to link with other tables
		$contact = $this->loadTable('Contact');
		$prospect = $this->loadTable('Client');
		$contact_type = $this->loadTable('ContactType');

		$params[":prospect_id"] = $prospectId;

		$sql = "SELECT 
					contact_link.public_id as contact_link,
					contact_link.poa,
					contact_link.primary_contact,
					contact.public_id,
					contact.first_name,
					contact.last_name,
					contact.email,
					contact.phone,
					contact.address,
					contact.city,
					contact.state,
					contact.zip,
					contact_type.name as contact_type
				FROM {$this->tableName()} as contact_link
				INNER JOIN {$prospect->tableName()} as prospect 
					ON prospect.id = contact_link.client
				INNER JOIN {$contact->tableName()} as contact 
					ON contact.id = contact_link.contact
				INNER JOIN {$contact_type->tableName()} as contact_type 
					ON contact_type.id = contact_link.contact_type
				WHERE prospect.id = :prospect_id
		";

		return $this->fetchAll($sql, $params);

	}


	public function deleteCurrentLinks($contactId, $prospectId) {
		$params[":contact_id"] = $contactId;
		$params[":prospect_id"] = $prospectId;

		$sql = "DELETE FROM {$this->tableName()} WHERE contact = :contact_id AND client = :prospect_id";


		$this->deleteQuery($sql, $params);
		return $this;
 
				
	}

	public function findExisting($prospect, $contact, $contact_type = false) {

		$params = array(
			":prospect" => $prospect->id,
			":contact" => $contact->id,
		);

		if ($contact_type) {
			$params[":contact_type"] = $contact_type;
		}

		$sql = "SELECT id FROM {$this->tableName()} WHERE client = :prospect AND contact = :contact";

		if ($contact_type) {
			$sql .= " AND contact_type = :contact_type";
		}

		if ($this->fetchOne($sql, $params)) {
			return $this;
		} 

		return $this;

	}


	public function findExistingLink($prospect, $contact) {
		$params = array(
			":prospect_id" => $prospect->id,
			":contact_id" => $contact->id
		);

		$sql = "SELECT * FROM {$this->tableName()} as cl WHERE cl.client = :prospect_id AND cl.contact = :contact_id";

		return $this->fetchOne($sql, $params);
	}


	public function unlinkContact($prospect, $contact, $contact_link) {

		$params = array(
			":prospect" 	=> $prospect,
			":contact" 		=> $contact,
			":contact_link" => $contact_link
		);

		$sql = "DELETE from {$this->tableName()} WHERE id = :contact_link AND client = :prospect AND contact = :contact";

		return $this->deleteQuery($sql, $params);

	}

	public function checkExistingLegalAuthority($prospect, $legal_authority) {
		$params[":prospect"] = $prospect;


		$sql = "SELECT id FROM {$this->tableName()} WHERE client = :prospect AND";

		if ($legal_authority == 'poa') {
			$sql .= " poa = 1";
		} elseif ($legal_authority == 'primary_contact') {
			$sql .= " primary_contact = 1";
		}


		$legal_type = $this->fetchOne($sql, $params);

		$params = array();

		if (!empty ($legal_type)) {
			$params[":id"] = $legal_type->id;
			
			if ($legal_authority == 'poa') {
				$sql = "UPDATE {$this->tableName()} SET poa = 0 WHERE id = :id";
			} elseif ($legal_authority == 'primary_contact') {
				$sql = "UPDATE {$this->tableName()} SET primary_contact = 0 WHERE id = :id";
			}

			$this->update($sql, $params);
			return true;
		}

		return false;
	}

}