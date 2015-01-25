package es.basa.s3a.model;

import java.util.Set;

import javax.persistence.Entity;
import javax.persistence.OneToMany;

import es.basa.s3a.model.enums.Role;
import es.basa.s3a.model.enums.UserState;
import es.basa.s3a.security.SocialMediaService;

@Entity
public class User extends BaseModelObject {

	private String username;
	private String password;
	private String email;
	private Role role;
	private UserState userState;
	private SocialMediaService signInProvider;

	@OneToMany(mappedBy = "user")
	private Set<UserPermission> userPermissions;

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

	public Set<UserPermission> getUserPermissions() {
		return userPermissions;
	}

	public void setUserPermissions(Set<UserPermission> userPermissions) {
		this.userPermissions = userPermissions;
	}

	public Role getRole() {
		return role;
	}

	public void setRole(Role role) {
		this.role = role;
	}

	public SocialMediaService getSignInProvider() {
		return signInProvider;
	}

	public void setSignInProvider(SocialMediaService signInProvider) {
		this.signInProvider = signInProvider;
	}

	@Override
	public String toString() {
		return "id=" + id + ", name=" + username + ", email=" + email;
	}

}
