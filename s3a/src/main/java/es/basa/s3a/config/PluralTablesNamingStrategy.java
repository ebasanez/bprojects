package es.basa.s3a.config;

import org.hibernate.cfg.ImprovedNamingStrategy;

public class PluralTablesNamingStrategy extends ImprovedNamingStrategy {

	private static final long serialVersionUID = 5457601503628022449L;
	private static final String PLURAL_SUFFIX = "s";

	@Override
	public String classToTableName(String className) {
		String tableNameInSingularForm = super.classToTableName(className);
		return transformToPluralForm(tableNameInSingularForm);
	}

	private String transformToPluralForm(String tableNameInSingularForm) {
		StringBuilder pluralForm = new StringBuilder();

		pluralForm.append(tableNameInSingularForm);
		pluralForm.append(PLURAL_SUFFIX);

		return pluralForm.toString();
	}

}
