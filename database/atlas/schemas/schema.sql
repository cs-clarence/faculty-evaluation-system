--
-- PostgreSQL database dump
--

--
-- Name: set_order_numerator(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE OR REPLACE FUNCTION public.set_order_numerator() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
        current_max INTEGER;
    BEGIN
      IF NEW.order_numerator = 0 THEN
          -- Use dynamic SQL to query the maximum order_numerator value from the table
          EXECUTE format('SELECT COALESCE(MAX(order_numerator), 0) FROM %I', TG_TABLE_NAME)
            INTO current_max;

          NEW.order_numerator := current_max + 1;
      END IF;
      RETURN NEW;
    END;
    $$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: course_semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_semesters (
    id bigint NOT NULL,
    year_level integer NOT NULL,
    semester integer NOT NULL,
    course_id integer NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: course_semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_semesters_id_seq OWNED BY public.course_semesters.id;


--
-- Name: course_subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_subjects (
    id bigint NOT NULL,
    course_semester_id bigint NOT NULL,
    subject_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: course_subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_subjects_id_seq OWNED BY public.course_subjects.id;


--
-- Name: courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    department_id integer NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;


--
-- Name: deans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.deans (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    department_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: deans_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.deans_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: deans_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.deans_id_seq OWNED BY public.deans.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departments (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: evaluators; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.evaluators (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: evaluators_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.evaluators_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: evaluators_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.evaluators_id_seq OWNED BY public.evaluators.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: form_question_essay_type_configurations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_question_essay_type_configurations (
    id bigint NOT NULL,
    form_question_id bigint NOT NULL,
    value_scale_from double precision NOT NULL,
    value_scale_to double precision NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_question_essay_type_configurations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_question_essay_type_configurations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_question_essay_type_configurations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_question_essay_type_configurations_id_seq OWNED BY public.form_question_essay_type_configurations.id;


--
-- Name: form_question_options; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_question_options (
    id bigint NOT NULL,
    label character varying(255) NOT NULL,
    interpretation character varying(255),
    value double precision NOT NULL,
    form_question_id bigint NOT NULL,
    order_numerator integer NOT NULL,
    order_denominator integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_question_options_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_question_options_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_question_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_question_options_id_seq OWNED BY public.form_question_options.id;


--
-- Name: form_questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_questions (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description character varying(255),
    type character varying(255) NOT NULL,
    weight double precision DEFAULT '1'::double precision NOT NULL,
    form_id bigint NOT NULL,
    form_section_id bigint NOT NULL,
    order_numerator integer NOT NULL,
    order_denominator integer DEFAULT 1 NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_questions_id_seq OWNED BY public.form_questions.id;


--
-- Name: form_sections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_sections (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description character varying(255),
    form_id bigint NOT NULL,
    order_numerator integer NOT NULL,
    order_denominator integer DEFAULT 1 NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_sections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_sections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_sections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_sections_id_seq OWNED BY public.form_sections.id;


--
-- Name: form_submission_answer_selected_options; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_answer_selected_options (
    id bigint NOT NULL,
    form_submission_answer_id bigint NOT NULL,
    form_question_option_id bigint NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_submission_answer_selected_options_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_answer_selected_options_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_answer_selected_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_answer_selected_options_id_seq OWNED BY public.form_submission_answer_selected_options.id;


--
-- Name: form_submission_answers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_answers (
    id bigint NOT NULL,
    form_submission_id bigint NOT NULL,
    form_question_id bigint NOT NULL,
    value double precision NOT NULL,
    text character varying(10239),
    interpretation character varying(10239),
    reason character varying(10239),
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_submission_answers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_answers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_answers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_answers_id_seq OWNED BY public.form_submission_answers.id;


--
-- Name: form_submission_departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_departments (
    id bigint NOT NULL,
    department_id bigint NOT NULL,
    form_submission_id bigint NOT NULL
);


--
-- Name: form_submission_departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_departments_id_seq OWNED BY public.form_submission_departments.id;


--
-- Name: form_submission_period_semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_period_semesters (
    id bigint NOT NULL,
    form_submission_period_id bigint NOT NULL,
    semester_id bigint NOT NULL
);


--
-- Name: form_submission_period_semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_period_semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_period_semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_period_semesters_id_seq OWNED BY public.form_submission_period_semesters.id;


--
-- Name: form_submission_periods; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_periods (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    starts_at timestamp(0) with time zone NOT NULL,
    ends_at timestamp(0) with time zone NOT NULL,
    is_open boolean NOT NULL,
    is_submissions_editable boolean NOT NULL,
    archived_at timestamp(0) with time zone,
    evaluator_role_id bigint NOT NULL,
    evaluatee_role_id bigint NOT NULL,
    form_id bigint NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_submission_periods_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_periods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_periods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_periods_id_seq OWNED BY public.form_submission_periods.id;


--
-- Name: form_submission_subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submission_subjects (
    id bigint NOT NULL,
    course_subject_id bigint NOT NULL,
    student_subject_id bigint,
    form_submission_id bigint NOT NULL
);


--
-- Name: form_submission_subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submission_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submission_subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submission_subjects_id_seq OWNED BY public.form_submission_subjects.id;


--
-- Name: form_submissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.form_submissions (
    id bigint NOT NULL,
    evaluator_id bigint NOT NULL,
    evaluatee_id bigint NOT NULL,
    form_submission_period_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: form_submissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.form_submissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: form_submissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.form_submissions_id_seq OWNED BY public.form_submissions.id;


--
-- Name: forms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.forms (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255),
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: forms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.forms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: forms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.forms_id_seq OWNED BY public.forms.id;


--
-- Name: human_resources_staff; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.human_resources_staff (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: human_resources_staff_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.human_resources_staff_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: human_resources_staff_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.human_resources_staff_id_seq OWNED BY public.human_resources_staff.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) with time zone
);


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) with time zone,
    expires_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    display_name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    hidden boolean NOT NULL,
    can_be_evaluator boolean DEFAULT false NOT NULL,
    can_be_evaluatee boolean DEFAULT true NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: school_years; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.school_years (
    id bigint NOT NULL,
    year_start integer NOT NULL,
    year_end integer NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: school_years_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.school_years_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: school_years_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.school_years_id_seq OWNED BY public.school_years.id;


--
-- Name: sections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sections (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    year_level integer NOT NULL,
    semester integer NOT NULL,
    course_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: sections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: sections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.sections_id_seq OWNED BY public.sections.id;


--
-- Name: semester_sections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.semester_sections (
    id bigint NOT NULL,
    section_id bigint NOT NULL,
    semester_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: semester_sections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.semester_sections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: semester_sections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.semester_sections_id_seq OWNED BY public.semester_sections.id;


--
-- Name: semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.semesters (
    id bigint NOT NULL,
    semester integer NOT NULL,
    school_year_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.semesters_id_seq OWNED BY public.semesters.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: student_semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_semesters (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    semester_id bigint NOT NULL,
    semester_section_id bigint,
    course_semester_id bigint NOT NULL,
    year_level integer NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: student_semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_semesters_id_seq OWNED BY public.student_semesters.id;


--
-- Name: student_subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_subjects (
    id bigint NOT NULL,
    student_semester_id bigint NOT NULL,
    course_subject_id bigint NOT NULL,
    semester_section_id bigint,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: student_subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_subjects_id_seq OWNED BY public.student_subjects.id;


--
-- Name: students; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.students (
    id bigint NOT NULL,
    student_number character varying(255) NOT NULL,
    address character varying(255),
    starting_school_year_id bigint NOT NULL,
    user_id bigint NOT NULL,
    course_id bigint NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone,
    archived_at timestamp(0) with time zone
);


--
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.students_id_seq OWNED BY public.students.id;


--
-- Name: subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.subjects (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.subjects_id_seq OWNED BY public.subjects.id;


--
-- Name: teacher_semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teacher_semesters (
    id bigint NOT NULL,
    teacher_id bigint NOT NULL,
    semester_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: teacher_semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teacher_semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teacher_semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teacher_semesters_id_seq OWNED BY public.teacher_semesters.id;


--
-- Name: teacher_subject_semester_sections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teacher_subject_semester_sections (
    id bigint NOT NULL,
    teacher_subject_id bigint NOT NULL,
    semester_section_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: teacher_subject_semester_sections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teacher_subject_semester_sections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teacher_subject_semester_sections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teacher_subject_semester_sections_id_seq OWNED BY public.teacher_subject_semester_sections.id;


--
-- Name: teacher_subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teacher_subjects (
    id bigint NOT NULL,
    teacher_semester_id bigint NOT NULL,
    course_subject_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: teacher_subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teacher_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teacher_subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teacher_subjects_id_seq OWNED BY public.teacher_subjects.id;


--
-- Name: teachers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teachers (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    department_id bigint NOT NULL,
    archived_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: teachers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.teachers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: teachers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.teachers_id_seq OWNED BY public.teachers.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    role_id bigint NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) with time zone,
    require_change_password boolean DEFAULT false NOT NULL,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    archived_at timestamp(0) with time zone,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: course_semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_semesters ALTER COLUMN id SET DEFAULT nextval('public.course_semesters_id_seq'::regclass);


--
-- Name: course_subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_subjects ALTER COLUMN id SET DEFAULT nextval('public.course_subjects_id_seq'::regclass);


--
-- Name: courses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);


--
-- Name: deans id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deans ALTER COLUMN id SET DEFAULT nextval('public.deans_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: evaluators id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.evaluators ALTER COLUMN id SET DEFAULT nextval('public.evaluators_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: form_question_essay_type_configurations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_essay_type_configurations ALTER COLUMN id SET DEFAULT nextval('public.form_question_essay_type_configurations_id_seq'::regclass);


--
-- Name: form_question_options id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_options ALTER COLUMN id SET DEFAULT nextval('public.form_question_options_id_seq'::regclass);


--
-- Name: form_questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions ALTER COLUMN id SET DEFAULT nextval('public.form_questions_id_seq'::regclass);


--
-- Name: form_sections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_sections ALTER COLUMN id SET DEFAULT nextval('public.form_sections_id_seq'::regclass);


--
-- Name: form_submission_answer_selected_options id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answer_selected_options ALTER COLUMN id SET DEFAULT nextval('public.form_submission_answer_selected_options_id_seq'::regclass);


--
-- Name: form_submission_answers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answers ALTER COLUMN id SET DEFAULT nextval('public.form_submission_answers_id_seq'::regclass);


--
-- Name: form_submission_departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments ALTER COLUMN id SET DEFAULT nextval('public.form_submission_departments_id_seq'::regclass);


--
-- Name: form_submission_period_semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters ALTER COLUMN id SET DEFAULT nextval('public.form_submission_period_semesters_id_seq'::regclass);


--
-- Name: form_submission_periods id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_periods ALTER COLUMN id SET DEFAULT nextval('public.form_submission_periods_id_seq'::regclass);


--
-- Name: form_submission_subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects ALTER COLUMN id SET DEFAULT nextval('public.form_submission_subjects_id_seq'::regclass);


--
-- Name: form_submissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submissions ALTER COLUMN id SET DEFAULT nextval('public.form_submissions_id_seq'::regclass);


--
-- Name: forms id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.forms ALTER COLUMN id SET DEFAULT nextval('public.forms_id_seq'::regclass);


--
-- Name: human_resources_staff id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.human_resources_staff ALTER COLUMN id SET DEFAULT nextval('public.human_resources_staff_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: school_years id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.school_years ALTER COLUMN id SET DEFAULT nextval('public.school_years_id_seq'::regclass);


--
-- Name: sections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sections ALTER COLUMN id SET DEFAULT nextval('public.sections_id_seq'::regclass);


--
-- Name: semester_sections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_sections ALTER COLUMN id SET DEFAULT nextval('public.semester_sections_id_seq'::regclass);


--
-- Name: semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters ALTER COLUMN id SET DEFAULT nextval('public.semesters_id_seq'::regclass);


--
-- Name: student_semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters ALTER COLUMN id SET DEFAULT nextval('public.student_semesters_id_seq'::regclass);


--
-- Name: student_subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects ALTER COLUMN id SET DEFAULT nextval('public.student_subjects_id_seq'::regclass);


--
-- Name: students id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students ALTER COLUMN id SET DEFAULT nextval('public.students_id_seq'::regclass);


--
-- Name: subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subjects ALTER COLUMN id SET DEFAULT nextval('public.subjects_id_seq'::regclass);


--
-- Name: teacher_semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_semesters ALTER COLUMN id SET DEFAULT nextval('public.teacher_semesters_id_seq'::regclass);


--
-- Name: teacher_subject_semester_sections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subject_semester_sections ALTER COLUMN id SET DEFAULT nextval('public.teacher_subject_semester_sections_id_seq'::regclass);


--
-- Name: teacher_subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subjects ALTER COLUMN id SET DEFAULT nextval('public.teacher_subjects_id_seq'::regclass);


--
-- Name: teachers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers ALTER COLUMN id SET DEFAULT nextval('public.teachers_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: course_semesters course_semesters_course_id_year_level_semester_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_semesters
    ADD CONSTRAINT course_semesters_course_id_year_level_semester_unique UNIQUE (course_id, year_level, semester);


--
-- Name: course_semesters course_semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_semesters
    ADD CONSTRAINT course_semesters_pkey PRIMARY KEY (id);


--
-- Name: course_subjects course_subjects_course_semester_id_subject_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_subjects
    ADD CONSTRAINT course_subjects_course_semester_id_subject_id_unique UNIQUE (course_semester_id, subject_id);


--
-- Name: course_subjects course_subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_subjects
    ADD CONSTRAINT course_subjects_pkey PRIMARY KEY (id);


--
-- Name: courses courses_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_code_unique UNIQUE (code);


--
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- Name: deans deans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deans
    ADD CONSTRAINT deans_pkey PRIMARY KEY (id);


--
-- Name: departments departments_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_name_unique UNIQUE (name);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: evaluators evaluators_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.evaluators
    ADD CONSTRAINT evaluators_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: form_question_essay_type_configurations form_question_essay_type_configurations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_essay_type_configurations
    ADD CONSTRAINT form_question_essay_type_configurations_pkey PRIMARY KEY (id);


--
-- Name: form_question_options form_question_options_form_question_id_label_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_options
    ADD CONSTRAINT form_question_options_form_question_id_label_unique UNIQUE (form_question_id, label);


--
-- Name: form_question_options form_question_options_order_numerator_order_denominator_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_options
    ADD CONSTRAINT form_question_options_order_numerator_order_denominator_unique UNIQUE (order_numerator, order_denominator);


--
-- Name: form_question_options form_question_options_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_options
    ADD CONSTRAINT form_question_options_pkey PRIMARY KEY (id);


--
-- Name: form_questions form_questions_order_numerator_order_denominator_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions
    ADD CONSTRAINT form_questions_order_numerator_order_denominator_unique UNIQUE (order_numerator, order_denominator);


--
-- Name: form_questions form_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions
    ADD CONSTRAINT form_questions_pkey PRIMARY KEY (id);


--
-- Name: form_questions form_questions_title_form_section_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions
    ADD CONSTRAINT form_questions_title_form_section_id_unique UNIQUE (title, form_section_id);


--
-- Name: form_sections form_sections_form_id_title_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_sections
    ADD CONSTRAINT form_sections_form_id_title_unique UNIQUE (form_id, title);


--
-- Name: form_sections form_sections_order_numerator_order_denominator_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_sections
    ADD CONSTRAINT form_sections_order_numerator_order_denominator_unique UNIQUE (order_numerator, order_denominator);


--
-- Name: form_sections form_sections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_sections
    ADD CONSTRAINT form_sections_pkey PRIMARY KEY (id);


--
-- Name: form_submission_answer_selected_options form_submission_answer_selected_options_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answer_selected_options
    ADD CONSTRAINT form_submission_answer_selected_options_pkey PRIMARY KEY (id);


--
-- Name: form_submission_answers form_submission_answers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answers
    ADD CONSTRAINT form_submission_answers_pkey PRIMARY KEY (id);


--
-- Name: form_submission_departments form_submission_departments_form_submission_id_department_id_un; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments
    ADD CONSTRAINT form_submission_departments_form_submission_id_department_id_un UNIQUE (form_submission_id, department_id);


--
-- Name: form_submission_departments form_submission_departments_form_submission_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments
    ADD CONSTRAINT form_submission_departments_form_submission_id_unique UNIQUE (form_submission_id);


--
-- Name: form_submission_departments form_submission_departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments
    ADD CONSTRAINT form_submission_departments_pkey PRIMARY KEY (id);


--
-- Name: form_submission_period_semesters form_submission_period_semesters_form_submission_period_id_uniq; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters
    ADD CONSTRAINT form_submission_period_semesters_form_submission_period_id_uniq UNIQUE (form_submission_period_id);


--
-- Name: form_submission_period_semesters form_submission_period_semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters
    ADD CONSTRAINT form_submission_period_semesters_pkey PRIMARY KEY (id);


--
-- Name: form_submission_periods form_submission_periods_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_periods
    ADD CONSTRAINT form_submission_periods_pkey PRIMARY KEY (id);


--
-- Name: form_submission_subjects form_submission_subjects_form_submission_id_course_subject_id_u; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_form_submission_id_course_subject_id_u UNIQUE (form_submission_id, course_subject_id);


--
-- Name: form_submission_subjects form_submission_subjects_form_submission_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_form_submission_id_unique UNIQUE (form_submission_id);


--
-- Name: form_submission_subjects form_submission_subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_pkey PRIMARY KEY (id);


--
-- Name: form_submission_subjects form_submission_subjects_student_subject_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_student_subject_id_unique UNIQUE (student_subject_id);


--
-- Name: form_submissions form_submissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submissions
    ADD CONSTRAINT form_submissions_pkey PRIMARY KEY (id);


--
-- Name: forms forms_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.forms
    ADD CONSTRAINT forms_pkey PRIMARY KEY (id);


--
-- Name: human_resources_staff human_resources_staff_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.human_resources_staff
    ADD CONSTRAINT human_resources_staff_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: roles roles_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_code_unique UNIQUE (code);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: school_years school_years_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.school_years
    ADD CONSTRAINT school_years_pkey PRIMARY KEY (id);


--
-- Name: sections sections_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sections
    ADD CONSTRAINT sections_code_unique UNIQUE (code);


--
-- Name: sections sections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sections
    ADD CONSTRAINT sections_pkey PRIMARY KEY (id);


--
-- Name: sections sections_year_level_semester_course_id_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sections
    ADD CONSTRAINT sections_year_level_semester_course_id_name_unique UNIQUE (year_level, semester, course_id, name);


--
-- Name: form_submission_period_semesters semester_id; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters
    ADD CONSTRAINT semester_id UNIQUE (form_submission_period_id);


--
-- Name: semester_sections semester_sections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_sections
    ADD CONSTRAINT semester_sections_pkey PRIMARY KEY (id);


--
-- Name: semester_sections semester_sections_section_id_semester_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_sections
    ADD CONSTRAINT semester_sections_section_id_semester_id_unique UNIQUE (section_id, semester_id);


--
-- Name: semesters semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_pkey PRIMARY KEY (id);


--
-- Name: semesters semesters_school_year_id_semester_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_school_year_id_semester_unique UNIQUE (school_year_id, semester);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: student_semesters student_semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_pkey PRIMARY KEY (id);


--
-- Name: student_semesters student_semesters_student_id_semester_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_student_id_semester_id_unique UNIQUE (student_id, semester_id);


--
-- Name: student_subjects student_subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects
    ADD CONSTRAINT student_subjects_pkey PRIMARY KEY (id);


--
-- Name: student_subjects student_subjects_student_semester_id_course_subject_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects
    ADD CONSTRAINT student_subjects_student_semester_id_course_subject_id_unique UNIQUE (student_semester_id, course_subject_id);


--
-- Name: students students_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- Name: students students_student_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_student_number_unique UNIQUE (student_number);


--
-- Name: subjects subjects_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subjects
    ADD CONSTRAINT subjects_code_unique UNIQUE (code);


--
-- Name: subjects subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subjects
    ADD CONSTRAINT subjects_pkey PRIMARY KEY (id);


--
-- Name: teacher_semesters teacher_semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_semesters
    ADD CONSTRAINT teacher_semesters_pkey PRIMARY KEY (id);


--
-- Name: teacher_semesters teacher_semesters_teacher_id_semester_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_semesters
    ADD CONSTRAINT teacher_semesters_teacher_id_semester_id_unique UNIQUE (teacher_id, semester_id);


--
-- Name: teacher_subject_semester_sections teacher_subject_semester_sections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subject_semester_sections
    ADD CONSTRAINT teacher_subject_semester_sections_pkey PRIMARY KEY (id);


--
-- Name: teacher_subject_semester_sections teacher_subject_semester_sections_teacher_subject_id_semester_s; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subject_semester_sections
    ADD CONSTRAINT teacher_subject_semester_sections_teacher_subject_id_semester_s UNIQUE (teacher_subject_id, semester_section_id);


--
-- Name: teacher_subjects teacher_subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subjects
    ADD CONSTRAINT teacher_subjects_pkey PRIMARY KEY (id);


--
-- Name: teacher_subjects teacher_subjects_teacher_semester_id_course_subject_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subjects
    ADD CONSTRAINT teacher_subjects_teacher_semester_id_course_subject_id_unique UNIQUE (teacher_semester_id, course_subject_id);


--
-- Name: teachers teachers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: school_years year_end; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.school_years
    ADD CONSTRAINT year_end UNIQUE (year_start);


--
-- Name: courses_name_code_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX courses_name_code_fulltext ON public.courses USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));


--
-- Name: departments_name_code_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX departments_name_code_fulltext ON public.departments USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));


--
-- Name: form_question_options_label_interpretation_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX form_question_options_label_interpretation_fulltext ON public.form_question_options USING gin (((to_tsvector('english'::regconfig, (label)::text) || to_tsvector('english'::regconfig, (interpretation)::text))));


--
-- Name: form_questions_title_description_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX form_questions_title_description_fulltext ON public.form_questions USING gin (((to_tsvector('english'::regconfig, (title)::text) || to_tsvector('english'::regconfig, (description)::text))));


--
-- Name: form_sections_title_description_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX form_sections_title_description_fulltext ON public.form_sections USING gin (((to_tsvector('english'::regconfig, (title)::text) || to_tsvector('english'::regconfig, (description)::text))));


--
-- Name: form_submission_answers_text_interpretation_reason_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX form_submission_answers_text_interpretation_reason_fulltext ON public.form_submission_answers USING gin ((((to_tsvector('english'::regconfig, (text)::text) || to_tsvector('english'::regconfig, (interpretation)::text)) || to_tsvector('english'::regconfig, (reason)::text))));


--
-- Name: forms_name_description_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX forms_name_description_fulltext ON public.forms USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (description)::text))));


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: roles_display_name_code_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX roles_display_name_code_fulltext ON public.roles USING gin (((to_tsvector('english'::regconfig, (display_name)::text) || to_tsvector('english'::regconfig, (code)::text))));


--
-- Name: sections_name_code_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sections_name_code_fulltext ON public.sections USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: subjects_name_code_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX subjects_name_code_fulltext ON public.subjects USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));


--
-- Name: users_name_email_fulltext; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_name_email_fulltext ON public.users USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (email)::text))));


--
-- Name: form_question_options set_order_numerator_form_question_options; Type: TRIGGER; Schema: public; Owner: -
--

CREATE OR REPLACE TRIGGER set_order_numerator_form_question_options BEFORE INSERT ON public.form_question_options FOR EACH ROW EXECUTE FUNCTION public.set_order_numerator();


--
-- Name: form_questions set_order_numerator_form_questions; Type: TRIGGER; Schema: public; Owner: -
--

CREATE OR REPLACE TRIGGER set_order_numerator_form_questions BEFORE INSERT ON public.form_questions FOR EACH ROW EXECUTE FUNCTION public.set_order_numerator();


--
-- Name: form_sections set_order_numerator_form_sections; Type: TRIGGER; Schema: public; Owner: -
--

CREATE OR REPLACE TRIGGER set_order_numerator_form_sections BEFORE INSERT ON public.form_sections FOR EACH ROW EXECUTE FUNCTION public.set_order_numerator();


--
-- Name: course_semesters course_semesters_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_semesters
    ADD CONSTRAINT course_semesters_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: course_subjects course_subjects_course_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_subjects
    ADD CONSTRAINT course_subjects_course_semester_id_foreign FOREIGN KEY (course_semester_id) REFERENCES public.course_semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: course_subjects course_subjects_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_subjects
    ADD CONSTRAINT course_subjects_subject_id_foreign FOREIGN KEY (subject_id) REFERENCES public.subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: courses courses_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: deans deans_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deans
    ADD CONSTRAINT deans_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: deans deans_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deans
    ADD CONSTRAINT deans_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: evaluators evaluators_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.evaluators
    ADD CONSTRAINT evaluators_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_question_essay_type_configurations form_question_essay_type_configurations_form_question_id_foreig; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_essay_type_configurations
    ADD CONSTRAINT form_question_essay_type_configurations_form_question_id_foreig FOREIGN KEY (form_question_id) REFERENCES public.form_questions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_question_options form_question_options_form_question_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_question_options
    ADD CONSTRAINT form_question_options_form_question_id_foreign FOREIGN KEY (form_question_id) REFERENCES public.form_questions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_questions form_questions_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions
    ADD CONSTRAINT form_questions_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.forms(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_questions form_questions_form_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_questions
    ADD CONSTRAINT form_questions_form_section_id_foreign FOREIGN KEY (form_section_id) REFERENCES public.form_sections(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_sections form_sections_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_sections
    ADD CONSTRAINT form_sections_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.forms(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_answer_selected_options form_submission_answer_selected_options_form_question_option_id; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answer_selected_options
    ADD CONSTRAINT form_submission_answer_selected_options_form_question_option_id FOREIGN KEY (form_question_option_id) REFERENCES public.form_question_options(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_answer_selected_options form_submission_answer_selected_options_form_submission_answer_; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answer_selected_options
    ADD CONSTRAINT form_submission_answer_selected_options_form_submission_answer_ FOREIGN KEY (form_submission_answer_id) REFERENCES public.form_submission_answers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_answers form_submission_answers_form_question_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answers
    ADD CONSTRAINT form_submission_answers_form_question_id_foreign FOREIGN KEY (form_question_id) REFERENCES public.form_questions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_answers form_submission_answers_form_submission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_answers
    ADD CONSTRAINT form_submission_answers_form_submission_id_foreign FOREIGN KEY (form_submission_id) REFERENCES public.form_submissions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_departments form_submission_departments_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments
    ADD CONSTRAINT form_submission_departments_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_departments form_submission_departments_form_submission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_departments
    ADD CONSTRAINT form_submission_departments_form_submission_id_foreign FOREIGN KEY (form_submission_id) REFERENCES public.form_submissions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_period_semesters form_submission_period_semesters_form_submission_period_id_fore; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters
    ADD CONSTRAINT form_submission_period_semesters_form_submission_period_id_fore FOREIGN KEY (form_submission_period_id) REFERENCES public.form_submission_periods(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_period_semesters form_submission_period_semesters_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_period_semesters
    ADD CONSTRAINT form_submission_period_semesters_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_periods form_submission_periods_evaluatee_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_periods
    ADD CONSTRAINT form_submission_periods_evaluatee_role_id_foreign FOREIGN KEY (evaluatee_role_id) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_periods form_submission_periods_evaluator_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_periods
    ADD CONSTRAINT form_submission_periods_evaluator_role_id_foreign FOREIGN KEY (evaluator_role_id) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_periods form_submission_periods_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_periods
    ADD CONSTRAINT form_submission_periods_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.forms(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_subjects form_submission_subjects_course_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_course_subject_id_foreign FOREIGN KEY (course_subject_id) REFERENCES public.course_subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_subjects form_submission_subjects_form_submission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_form_submission_id_foreign FOREIGN KEY (form_submission_id) REFERENCES public.form_submissions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submission_subjects form_submission_subjects_student_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submission_subjects
    ADD CONSTRAINT form_submission_subjects_student_subject_id_foreign FOREIGN KEY (student_subject_id) REFERENCES public.student_subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submissions form_submissions_evaluatee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submissions
    ADD CONSTRAINT form_submissions_evaluatee_id_foreign FOREIGN KEY (evaluatee_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submissions form_submissions_evaluator_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submissions
    ADD CONSTRAINT form_submissions_evaluator_id_foreign FOREIGN KEY (evaluator_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: form_submissions form_submissions_form_submission_period_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.form_submissions
    ADD CONSTRAINT form_submissions_form_submission_period_id_foreign FOREIGN KEY (form_submission_period_id) REFERENCES public.form_submission_periods(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: human_resources_staff human_resources_staff_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.human_resources_staff
    ADD CONSTRAINT human_resources_staff_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: sections sections_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sections
    ADD CONSTRAINT sections_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: semester_sections semester_sections_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_sections
    ADD CONSTRAINT semester_sections_section_id_foreign FOREIGN KEY (section_id) REFERENCES public.sections(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: semester_sections semester_sections_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_sections
    ADD CONSTRAINT semester_sections_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: semesters semesters_school_year_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_school_year_id_foreign FOREIGN KEY (school_year_id) REFERENCES public.school_years(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_semesters student_semesters_course_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_course_semester_id_foreign FOREIGN KEY (course_semester_id) REFERENCES public.course_semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_semesters student_semesters_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_semesters student_semesters_semester_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_semester_section_id_foreign FOREIGN KEY (semester_section_id) REFERENCES public.semester_sections(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_semesters student_semesters_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_semesters
    ADD CONSTRAINT student_semesters_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_subjects student_subjects_course_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects
    ADD CONSTRAINT student_subjects_course_subject_id_foreign FOREIGN KEY (course_subject_id) REFERENCES public.course_subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_subjects student_subjects_semester_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects
    ADD CONSTRAINT student_subjects_semester_section_id_foreign FOREIGN KEY (semester_section_id) REFERENCES public.semester_sections(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: student_subjects student_subjects_student_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_subjects
    ADD CONSTRAINT student_subjects_student_semester_id_foreign FOREIGN KEY (student_semester_id) REFERENCES public.student_semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: students students_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: students students_starting_school_year_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_starting_school_year_id_foreign FOREIGN KEY (starting_school_year_id) REFERENCES public.school_years(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: students students_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_semesters teacher_semesters_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_semesters
    ADD CONSTRAINT teacher_semesters_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_semesters teacher_semesters_teacher_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_semesters
    ADD CONSTRAINT teacher_semesters_teacher_id_foreign FOREIGN KEY (teacher_id) REFERENCES public.teachers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_subject_semester_sections teacher_subject_semester_sections_semester_section_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subject_semester_sections
    ADD CONSTRAINT teacher_subject_semester_sections_semester_section_id_foreign FOREIGN KEY (semester_section_id) REFERENCES public.semester_sections(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_subject_semester_sections teacher_subject_semester_sections_teacher_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subject_semester_sections
    ADD CONSTRAINT teacher_subject_semester_sections_teacher_subject_id_foreign FOREIGN KEY (teacher_subject_id) REFERENCES public.teacher_subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_subjects teacher_subjects_course_subject_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subjects
    ADD CONSTRAINT teacher_subjects_course_subject_id_foreign FOREIGN KEY (course_subject_id) REFERENCES public.course_subjects(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teacher_subjects teacher_subjects_teacher_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teacher_subjects
    ADD CONSTRAINT teacher_subjects_teacher_semester_id_foreign FOREIGN KEY (teacher_semester_id) REFERENCES public.teacher_semesters(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teachers teachers_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: teachers teachers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teachers
    ADD CONSTRAINT teachers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users users_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.0
-- Dumped by pg_dump version 16.3
