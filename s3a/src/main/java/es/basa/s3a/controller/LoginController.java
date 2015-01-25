package es.basa.s3a.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.servlet.ModelAndView;

//@Controller
public class LoginController {

	
	@RequestMapping(value="/logsin", method=RequestMethod.GET)
	public String  showLoginPage(){
		return "user/login";
	}
}
