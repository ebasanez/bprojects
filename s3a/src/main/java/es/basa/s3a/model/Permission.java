package es.basa.s3a.model;

import javax.persistence.Entity;

@Entity
public class Permission extends BaseModelObject {
	private String description;

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

}
