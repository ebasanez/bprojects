package es.basa.s3a.model;

import java.util.Set;

import javax.persistence.Entity;
import javax.persistence.OneToMany;

import es.basa.s3a.model.enums.UserState;

@Entity
public class User extends BaseModelObject {

	private String username;
	private String password;
	private String email;
	private UserState userState;

	@OneToMany(mappedBy = "user")
	private Set<UserRole> userRoles;

	public String getUsername() {
		return username;
	}

	public void setUsername(String username) {
		this.username = username;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public UserState getUserState() {
		return userState;
	}

	public void setUserState(UserState userState) {
		this.userState = userState;
	}

	public Set<UserRole> getUserRoles() {
		return userRoles;
	}

	public void setUserRoles(Set<UserRole> userRoles) {
		this.userRoles = userRoles;
	}

	@Override
	public String toString() {
		return "id=" + id + ", name=" + username + ", email=" + email;
	}

}
