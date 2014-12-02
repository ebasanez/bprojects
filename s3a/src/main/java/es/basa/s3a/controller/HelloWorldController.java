package es.basa.s3a.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.ModelAndView;

import es.basa.s3a.dao.UserDAO;

@Controller
public class HelloWorldController {
	String message = "Welcome to Spring MVC!";

	@Autowired
	UserDAO userDAO;
	
	@RequestMapping("/hello")
	public ModelAndView showMessage(@RequestParam(value = "name", required = false, defaultValue = "World") String name) {
		userDAO.list();
		ModelAndView mv = new ModelAndView("helloWorld");
		mv.addObject("message", message);
		mv.addObject("name", name);
		return mv;
	}
}
