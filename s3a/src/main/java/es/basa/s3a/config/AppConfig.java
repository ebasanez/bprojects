package es.basa.s3a.config;

import java.util.HashSet;
import java.util.Locale;
import java.util.Properties;
import java.util.Set;

import javax.sql.DataSource;

import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Import;
import org.springframework.context.annotation.PropertySource;
import org.springframework.context.support.ReloadableResourceBundleMessageSource;
import org.springframework.core.env.Environment;
import org.springframework.jdbc.datasource.DriverManagerDataSource;
import org.springframework.orm.hibernate4.HibernateTransactionManager;
import org.springframework.orm.hibernate4.LocalSessionFactoryBean;
import org.springframework.orm.hibernate4.support.OpenSessionInViewInterceptor;
import org.springframework.transaction.annotation.EnableTransactionManagement;
import org.springframework.web.servlet.LocaleResolver;
import org.springframework.web.servlet.ViewResolver;
import org.springframework.web.servlet.config.annotation.EnableWebMvc;
import org.springframework.web.servlet.config.annotation.InterceptorRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurerAdapter;
import org.springframework.web.servlet.i18n.LocaleChangeInterceptor;
import org.springframework.web.servlet.i18n.SessionLocaleResolver;
import org.thymeleaf.dialect.IDialect;
import org.thymeleaf.extras.springsecurity3.dialect.SpringSecurityDialect;
import org.thymeleaf.spring4.SpringTemplateEngine;
import org.thymeleaf.spring4.view.ThymeleafViewResolver;
import org.thymeleaf.templateresolver.ClassLoaderTemplateResolver;

@EnableWebMvc
@Configuration
@EnableTransactionManagement
@ComponentScan({ "es.basa.s3a.*" })
@Import({ SecurityConfig.class })
@PropertySource(value = { "classpath:application.properties" })
public class AppConfig extends WebMvcConfigurerAdapter {

	@Autowired
	private Environment environment;

	@Autowired
	private SessionFactory sessionFactory;

	// Configuracion de Spring MVC

	// Configuracion de thymeleaf
	@Bean
	public SpringTemplateEngine getTemplateEngine() {
		ClassLoaderTemplateResolver templateResolver = new ClassLoaderTemplateResolver();
		templateResolver.setTemplateMode("HTML5");
		templateResolver.setPrefix("views/");
		templateResolver.setSuffix(".html");

		Set<IDialect> dialects = new HashSet<IDialect>();
		dialects.add(new SpringSecurityDialect());
		// Por si más adelante introduzco "tags" de layout. Haria falta también añadir un segundo templateResolver para los layouts.
		// dialects.add(new LayoutDialect());

		final SpringTemplateEngine templateEngine = new SpringTemplateEngine();
		templateEngine.setTemplateResolver(templateResolver);
		templateEngine.setAdditionalDialects(dialects);
		templateEngine.setTemplateResolver(templateResolver);
		return templateEngine;
	}

	@Bean
	public ViewResolver viewResolver() {
		SpringTemplateEngine engine = getTemplateEngine();
		ThymeleafViewResolver viewResolver = new ThymeleafViewResolver();
		viewResolver.setTemplateEngine(engine);
		return viewResolver;
	}

	// TODO Implementar las vistas asociadas a los códigos de error:

	// @Bean
	// public SimpleMappingExceptionResolver exceptionResolver() {
	// SimpleMappingExceptionResolver exceptionResolver = new SimpleMappingExceptionResolver();
	//
	// Properties exceptionMappings = new Properties();
	//
	// exceptionMappings.put("java.lang.Exception", "error/error");
	// exceptionMappings.put("java.lang.RuntimeException", "error/error");
	//
	// exceptionResolver.setExceptionMappings(exceptionMappings);
	//
	// Properties statusCodes = new Properties();
	//
	// statusCodes.put("error/404", "404");
	// statusCodes.put("error/error", "500");
	//
	// exceptionResolver.setStatusCodes(statusCodes);
	//
	// return exceptionResolver;
	// }

	// @Bean(name = "multipartResolver")
	// public CommonsMultipartResolver getMultipartResolver() {
	// return new CommonsMultipartResolver();
	// }

	@Bean(name = "messageSource")
	public ReloadableResourceBundleMessageSource getMessageSource() {
		ReloadableResourceBundleMessageSource resource = new ReloadableResourceBundleMessageSource();
		resource.setBasename("classpath:messages");
		resource.setDefaultEncoding("UTF-8");
		// resource.getMessage("public.label.login.acceder", new Object[0],new Locale("es"));
		return resource;
	}

	@Bean
	public LocaleResolver localeResolver() {
		SessionLocaleResolver lr = new SessionLocaleResolver();
		// Dejamos el inglés por defecto, pero podríamos cambiarlo a español aquí
		lr.setDefaultLocale(Locale.ENGLISH);
		return lr;
	}

	@Override
	public void addInterceptors(final InterceptorRegistry registry) {
		OpenSessionInViewInterceptor osiv = new OpenSessionInViewInterceptor();
		osiv.setSessionFactory(sessionFactory);
		registry.addWebRequestInterceptor(osiv);
		registry.addInterceptor(new LocaleChangeInterceptor());
	}

	// Configutación de Hibernate
	@Bean
	public LocalSessionFactoryBean sessionFactory() {
		LocalSessionFactoryBean sessionFactory = new LocalSessionFactoryBean();
		sessionFactory.setDataSource(dataSource());
		sessionFactory.setPackagesToScan(new String[] { "es.basa.s3a.model" });
		sessionFactory.setHibernateProperties(hibernateProperties());
		sessionFactory.setNamingStrategy(new PluralTablesNamingStrategy());
		return sessionFactory;
	}

	@Bean
	public DataSource dataSource() {
		DriverManagerDataSource dataSource = new DriverManagerDataSource();
		dataSource.setDriverClassName(environment.getRequiredProperty("ddbb.driver"));
		dataSource.setUrl(environment.getRequiredProperty("ddbb.url"));
		dataSource.setUsername(environment.getRequiredProperty("ddbb.username"));
		dataSource.setPassword(environment.getRequiredProperty("ddbb.password"));
		return dataSource;
	}

	private Properties hibernateProperties() {
		Properties properties = new Properties();
		properties.put("hibernate.show_sql", environment.getRequiredProperty("hibernate.show_sql"));
		properties.put("hibernate.format_sql", environment.getRequiredProperty("hibernate.format_sql"));
		properties.put("hibernate.dialect", environment.getRequiredProperty("hibernate.dialect"));
		return properties;
	}

	@Bean
	@Autowired
	public HibernateTransactionManager transactionManager(SessionFactory s) {
		HibernateTransactionManager txManager = new HibernateTransactionManager();
		txManager.setSessionFactory(s);
		return txManager;
	}
}
