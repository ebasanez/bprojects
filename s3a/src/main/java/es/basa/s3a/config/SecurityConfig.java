package es.basa.s3a.config;

import javax.sql.DataSource;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.authentication.builders.AuthenticationManagerBuilder;
import org.springframework.security.config.annotation.method.configuration.EnableGlobalMethodSecurity;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configuration.WebSecurityConfigurerAdapter;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.social.security.SocialUserDetailsService;
import org.springframework.social.security.SpringSocialConfigurer;

import es.basa.s3a.dao.UserDAO;
import es.basa.s3a.service.impl.SocialUserDetailsServiceImpl;
import es.basa.s3a.service.impl.UserDetailsServiceImpl;

@Configuration
@EnableWebSecurity
@EnableGlobalMethodSecurity(prePostEnabled = true)
public class SecurityConfig extends WebSecurityConfigurerAdapter {

	@Autowired
	private DataSource dataSource;

	@Autowired
	private UserDAO userDAO;

	@Autowired
	public void configureGlobal(AuthenticationManagerBuilder auth) throws Exception {
		auth.jdbcAuthentication()
				.dataSource(dataSource)
				.usersByUsernameQuery("select username,password, CASE WHEN user_state = 1 then 1 ELSE 0 END as enabled from users where username=?")
				.authoritiesByUsernameQuery(
						"select u.username, p.key from user_permissions up, users u, permissions p where u.username=? and u.id = up.user_id and p.id = up.permission_id");
	}

	@Override
	protected void configure(HttpSecurity http) throws Exception {
		// http.authorizeRequests().antMatchers("/**").access("hasRole('USER')").and().formLogin().and()
		// .apply(new SpringSocialConfigurer().postLoginUrl("/").alwaysUsePostLoginUrl(true));
		// ;
		http

		// Configures form login
		.formLogin()
		// TODO Generar nuestra página de login
		// Comentamos por ahora para que nos cree la página de login Spring Security
		// .loginPage("/login")
		// .loginProcessingUrl("/login/authenticate")
		// .failureUrl("/login?error=bad_credentials")

				// Configures the logout function
				.and().logout().deleteCookies("JSESSIONID").logoutUrl("/logout").logoutSuccessUrl("/login")
				// Configures url based authorization
				.and().authorizeRequests()

				// Anyone can access the urls
				.antMatchers("/auth/**", "/login", "/signup/**", "/user/register/**").permitAll()
				// The rest of the our application is protected.
				// .permitAll().antMatchers("/**").hasRole("USER")
				.and().authorizeRequests().antMatchers("/**").access("hasRole('USER')")
				// Adds the SocialAuthenticationFilter to Spring Security's filter chain.
				.and().apply(new SpringSocialConfigurer());
	}

	// TODO Guardar las contraseñas hasheadas.
	// @Bean
	// public PasswordEncoder passwordEncoder() {
	// return new BCryptPasswordEncoder(10);
	// }

	@Bean
	public SocialUserDetailsService socialUserDetailsService() {
		return new SocialUserDetailsServiceImpl(userDetailsService());
	}

	@Bean
	public UserDetailsService userDetailsService() {
		return new UserDetailsServiceImpl(userDAO);
	}

}