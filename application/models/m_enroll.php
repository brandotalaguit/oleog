<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_enroll extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    function get_year_level($curriculum_id, $college_id, $LengthOfStayBySem)
    {
        //if ($college_id == 6 && !in_array($curriculum_id, array('53'))) 
    if (in_array($college_id, array('6','11')) && !in_array($curriculum_id, array('53')))
        {
            $data['semester'] = array(  1  => 'First Year' , 
                                        2  => 'First Year', 
                                        3  => 'First Year', 
                                        4  => 'Second Year', 
                                        5  => 'Second Year', 
                                        6  => 'Second Year', 
                                        7  => 'Third Year', 
                                        8  => 'Third Year',
                                        9  => 'Third Year',
                                        10 => 'Fourth Year', 
                                        11 => 'Fourth Year'); 

        }
        else
        {
            switch ($curriculum_id) 
            {
                case '55':  # BACHELOR OF SCIENCE IN CIVIL ENGINEERING
                    $data['semester'] = array(  1  => 'First Year' , 
                                                2  => 'First Year', 
                                                3  => 'Second Year', 
                                                4  => 'Second Year', 
                                                5  => 'Third Year', 
                                                6  => 'Third Year',
                                                7  => 'Fourth Year', 
                                                8  => 'Fourth Year',
                                                9  => 'Fourth Year', 
                                                10 => 'Fifth Year', 
                                                11 => 'Fifth Year');             
                    break;

                    case '53':  # BACHELOR OF SCIENCE IN RADIOLOGIC TECHNOLOGY
                    $data['semester'] = array(  1  =>'First Year' , 
                                                2  =>'First Year', 
                                                3  =>'First Year', 
                                                4  =>'Second Year', 
                                                5  =>'Second Year', 
                                                6  =>'Second Year', 
                                                7  =>'Third Year', 
                                                8  =>'Third Year',
                                                9  =>'Fourth Year', 
                                                10 =>'Fourth Year');
                    break;

                    case '25': # BACHELOR OF SCIENCE IN ACCOUNTANCY FIFTH YEAR PROGRAM
                    $data['semester'] = array(  1  =>'Fifth Year' , 
                                                2  =>'Fifth Year', 
                                                10  =>'Fifth Year', 
                                                11  =>'Fifth Year');
                    break;

                    case '66':  # DIPLOMA IN CIVIL TECHNOLOGY (DCET)
                    $data['semester'] = array(  1  =>'First Year' , 
                                                    2  =>'First Year', 
                                                    3  =>'Second Year', 
                                                    4  =>'Second Year', 
                                                    5  =>'Second Year', 
                                                    6  =>'Third Year', 
                                                    7  =>'Third Year');
                             break;

                    case '135':  # BACHELOR OF SCIENCE IN CONSTRUCTION ENGINEERING TECHNOLOGY
                    case '191':  # BACHELOR OF SCIENCE IN CONSTRUCTION ENGINEERING 2016
                    $data['semester'] = array(  1  => 'First Year', 
                                                2  => 'First Year', 
                                                3  => 'First Year', 
                                                4  => 'Second Year', 
                                                5  => 'Second Year', 
                                                6  => 'Second Year', 
                                                7  => 'Third Year', 
                                                8  => 'Third Year',
                                                9  => 'Third Year',
                                                10 => 'Fourth Year', 
                                                11 => 'Fourth Year'); 
                        break;

                    case '172':  # BACHELOR OF SCIENCE IN CIVIL ENGINEERING 2014
                    case '190':  # BACHELOR OF SCIENCE IN CIVIL ENGINEERING 2016
                    $data['semester'] = array(      1  => 'First Year', 
                                                    2  => 'First Year', 
                                                    3  => 'Second Year', 
                                                    4  => 'Second Year', 
                                                    5  => 'Third Year', 
                                                    6  => 'Third Year',
                                                    7  => 'Fourth Year', 
                                                    8  => 'Fourth Year',
                                                    9  => 'Fourth Year', 
                                                    10 => 'Fifth Year', 
                                                    11 => 'Fifth Year');             
                        break;

                    case '189':  # BACHELOR IN PHYSICAL WELLNESS MAJOR IN SPORTS MANAGEMENT
                    $data['semester'] = array(      1  => 'First Year', 
                                                    2  => 'First Year', 
                                                    3  => 'Second Year', 
                                                    4  => 'Second Year', 
                                                    5  => 'Second Year', 
                                                    6  => 'Third Year', 
                                                    7  => 'Third Year',
                                                    8  => 'Third Year', 
                                                    9  => 'Fourth Year', 
                                                    10  => 'Fourth Year');     
                        break;

                    
                default:    # REGULAR PROGRAM
                    $data['semester'] = array(  0  => '',
                                    1  => 'First Year' ,
                                                2  => 'First Year', 
                                                3  => 'Second Year', 
                                                4  => 'Second Year', 
                                                5  => 'Third Year', 
                                                6  => 'Third Year',
                                                7  => 'Fourth Year', 
                                                8  => 'Fourth Year',
                                                9  => 'Fifth Year', 
                                                10 => 'Fifth Year');             
                    break;

            } # end case 
            

        } # end if 

        return $data['semester'][$LengthOfStayBySem];
    }

    public function add_yrlevel($array)
    {
        $data = $array;

        foreach ($array as $key => $value) 
        $data[$key]['yr_level'] = self::get_year_level($value['CurriculumId'], $value['CollegeId'], $value['LengthOfStayBySem']);

        return $data;
    } 

}

/* End of file m_enroll.php */
/* Location: ./application/models/m_enroll.php */