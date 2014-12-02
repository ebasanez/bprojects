package es.basa.s3a.dao.impl;

import java.util.List;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import es.basa.s3a.dao.UserDAO;
import es.basa.s3a.model.User;

@Component
public class UserDAOImpl implements UserDAO {

	@Autowired
	private SessionFactory sessionFactory;

	@Transactional(readOnly = false)
	public void save(User entity) {
		Session session = sessionFactory.getCurrentSession();
		session.save(entity);

	}

	@SuppressWarnings("unchecked")
	@Transactional(readOnly = true)
	public List<User> list() {
		Session session = sessionFactory.getCurrentSession();
		return session.createCriteria(User.class).list();
	}

}
