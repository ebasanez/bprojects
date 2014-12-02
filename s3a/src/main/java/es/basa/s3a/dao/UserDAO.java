package es.basa.s3a.dao;

import java.util.List;

import es.basa.s3a.model.User;

public interface UserDAO {

	public void save(User entity);
	
	public List<User> list();
	
}
