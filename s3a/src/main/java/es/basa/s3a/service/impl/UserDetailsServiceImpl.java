package es.basa.s3a.service.impl;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;

import es.basa.s3a.dao.UserDAO;
import es.basa.s3a.model.User;
import es.basa.s3a.security.ExampleUserDetails;

public class UserDetailsServiceImpl implements UserDetailsService {

	private UserDAO userDAO;

	@Autowired
	public UserDetailsServiceImpl(UserDAO userDAO) {
		this.userDAO = userDAO;
	}

	@Override
	public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
		User user = userDAO.finfByEmail(username);
		if (user == null) {
			throw new UsernameNotFoundException("No user found with username: " + username);
		}

		ExampleUserDetails principal = ExampleUserDetails.getBuilder().firstName(user.getUsername()).id(user.getId()).lastName(user.getUsername())
				.password(user.getPassword()).role(user.getRole()).socialSignInProvider(user.getSignInProvider()).username(user.getEmail()).build();
		return principal;
	}

}
