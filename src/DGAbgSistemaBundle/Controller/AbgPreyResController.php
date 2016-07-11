<?php

namespace DGAbgSistemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DGAbgSistemaBundle\Entity\AbgPregunta;
use Doctrine\ORM\Query\ResultSetMapping;
use DGAbgSistemaBundle\Entity\AbgSubespecialidad;
use DGAbgSistemaBundle\Entity\CtlEspecialidad;


/**
 * Success controller.
 *
 * @Route("/preyres")
 */
class AbgPreyResController extends Controller {

    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/pregunta", name="pregunta", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction() {
        return $this->render('preyres/pregunta.html.twig');
    }

    /**
     * Esta funcion recibe el contenido de la pregunta en la interfaz inicial
     *
     * @Route("/preg_det", name="preg_deta", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function preguntaDetalleAction(Request $request) {

        $parameters = $request->request->all();
        //$pregunta =  $parameters['pregunta'];
        $pregunta = $request->get('txtpregunta');
        $prom = $this->busquedaPublicidad(1);
        $prom2 = $this->busquedaPublicidad(2);
        $prom3 = $this->busquedaPublicidad(3);
        $prom4 = $this->busquedaPublicidad(4);

        $rsm = new ResultSetMapping();

        $sql = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, count(pre.respuesta) as totalrespuestas
                from abg_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                inner join abg_persona per on usu.rh_persona_id = per.id
                inner join abg_foto foto on foto.abg_persona_id = per.id
                inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                group by per.nombres, per.apellido, foto.src, uper.url
                order by count(pre.respuesta) desc
                limit 0, 10";

        $rsm->addScalarResult('nombres', 'nombres');
        $rsm->addScalarResult('apellidos', 'apellidos');
        $rsm->addScalarResult('src', 'src');
        $rsm->addScalarResult('url', 'url');
        $rsm->addScalarResult('totalrespuestas', 'totalrespuestas');

        $em = $this->getDoctrine()->getManager();
        $topUsuarios = $em->createNativeQuery($sql, $rsm)
                ->getResult();

        $ctlespecialidad = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
        $emails = array('mario@digitalitygarage.com','anthony@digitalitygarage.com','elman@digitalitygarage.com','anthony.huezo@gmail.com'
            //'guillermo@digitalitygarage.com','anthony.huezo@gmail.com','mkstro.3@gmail.com','mecc_3@msn.com','jv648254@gmail.com','elman.ortiz@gmail.com',
            //'info@digitalitygarage.com','epresi07@gmail.com','design@digitalitygarage.com','mkstro.3@live.com','marvinvigilm@gmail.com','anthony.delgado985@gmail.com'
            );
        foreach($emails as $email){
            //$this->get('envio_correo')->sendEmail($email, "", "", "", "Prueba de envio masivo de correos para la plataforma de abogados.");
            //var_dump($email);
        }
        
        return $this->render('preyres/pregunta_detalle.html.twig', array('pregunta' => $pregunta,
                    'prom1' => $prom,
                    'prom2' => $prom2,
                    'prom3' => $prom3,
                    'prom4' => $prom4,
                    'ctlEspecialidad' => $ctlespecialidad,
                    'top' => $topUsuarios));
        
        
        
        
        
        
        
        
        
    }

    /**
     * Valida si una especialida tiene abogados registrados
     *
     * @Route("/preg_categoria", name="preg_categoria", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function PregCategoriaAction() {

        try {

            $em = $this->getDoctrine()->getManager();
            $request = $this->getRequest();
            $sql = "SELECT abg_persona.correoelectronico FROM 
                       ctl_usuario
                JOIN    abg_persona
                       on  ctl_usuario.rh_persona_id=abg_persona.id AND ctl_usuario.notificacion=1 AND abg_persona.estado IN(0,1)
                JOIN ctl_rol_usuario
                ON ctl_usuario.id=ctl_rol_usuario.ctl_usuario_id 
                          AND ctl_rol_usuario.ctl_rol_id=2
                JOIN abg_persona_especialidad 
                ON abg_persona_especialidad.abg_persona_id=abg_persona.id 
                AND abg_persona_especialidad.ctl_especialidad_id=" . $request->get('cat');
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $coenv = $stmt->fetchAll();

            if (count($coenv) > 0) {
                $data['value']=true;
            } else {
                  $data['value']=false;
            }
              return new Response(json_encode($data));
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
            return new Response(json_encode($data));
        }
    }

    /**
     * Muestra la interfaz para hacer una nueva pregunta.
     *
     * @Route("/enviopregunta", name="envio_pregunta", options={"expose"=true})
     * @Method("POST")
     * @Template()
     */
    public function envioPreguntaAction(Request $request) {

         $email;$comment;$captcha;
        if(isset($_POST['email'])){
            $email=$_POST['email'];
        }if(isset($_POST['comment'])){
            $email=$_POST['comment'];
        }if(isset($_POST['g-recaptcha-response'])){
            $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
            //Capctha sin llenar
//            echo "Estallo";
//            die();
            return $this->render('enviopregsuccess/error.html.twig');
            //echo '<h2>Please check the the captcha form.</h2>';
            //exit;
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc1QyQTAAAAAFKp2M-pijzAh-IxATFqKXsACd_G&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        $respuesta = json_decode($response);
        //var_dump(json_decode($response));
        //var_dump($respuesta->success);
        if($respuesta->success==false){
            
            //Captcha no valido
            return $this->render('enviopregsuccess/error.html.twig');
            //
            //echo '<h2>You are spammer ! Get the @$%K out</h2>';
        }
        else{
            try {
                $em = $this->getDoctrine()->getManager();
                $fechapregunta = date('Y-m-d');
                $parameters = $request->request->all();

                $pregunta = $parameters['pregunta'];
                $detalle = $parameters['detalle'];
                $especialidad = $parameters['especialidad'];

                $email = $parameters['email'];

                // publicidad
                $prom = $this->busquedaPublicidad(1);
                $prom2 = $this->busquedaPublicidad(2);
                $prom3 = $this->busquedaPublicidad(3);
                $prom4 = $this->busquedaPublicidad(4);
                $ctlespecialidad = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->findAll();
                $rsm = new ResultSetMapping();

                $sql = "select per.nombres as nombres, per.apellido as apellidos, foto.src as src, uper.url as url, count(pre.respuesta) as totalrespuestas
                    from abg_pregunta pre inner join ctl_usuario usu on pre.ctl_usuario_id = usu.id
                    inner join abg_persona per on usu.rh_persona_id = per.id
                    inner join abg_foto foto on foto.abg_persona_id = per.id
                    inner join abg_url_personalizada uper on uper.abg_persona_id = per.id and uper.estado=1
                    group by per.nombres, per.apellido, foto.src, uper.url
                    order by count(pre.respuesta) desc
                    limit 0, 10";

                $rsm->addScalarResult('nombres', 'nombres');
                $rsm->addScalarResult('apellidos', 'apellidos');
                $rsm->addScalarResult('src', 'src');
                $rsm->addScalarResult('url', 'url');
                $rsm->addScalarResult('totalrespuestas', 'totalrespuestas');

                $em = $this->getDoctrine()->getManager();
                $topUsuarios = $em->createNativeQuery($sql, $rsm)
                        ->getResult();

                $abgPregunta = new AbgPregunta();
                //$ctlSubespecialidad = new \DGAbgSistemaBundle\Entity\CtlSubespecialidad();
                $abgPregunta->setPregunta($pregunta);

                $abgPregunta->setDetalle($detalle);
                $abgPregunta->setEstado("2");
                $abgPregunta->setContador(0);
                $abgPregunta->setCorreoelectronico($email);
                $abgPregunta->setFechaPregunta(new \DateTime('now'));

                $em = $this->getDoctrine()->getManager();
                $espeid = $em->getRepository('DGAbgSistemaBundle:CtlEspecialidad')->find($especialidad);
                $abgPregunta->setAbgEspecialidad($espeid);

                $em->persist($abgPregunta);
                $em->flush();
                return $this->render('enviopregsuccess/success.html.twig');
            } catch (Exception $e) {
                $data['msj'] = $e->getMessage(); //"Falla al Registrar ";
                return new Response(json_encode($data));
            }
        }
    }

    private function busquedaPublicidad($posicion) {
        $em = $this->getDoctrine()->getManager();

        $i = 0;
        $recuperados = array();
        $prom = array();

        $dql = "Select fot.idargFoto, fot.src From DGAbgSistemaBundle:AbgFoto fot Join fot.promocion pro"
                . " WHERE pro.posicion = :posicion  and pro.estado = 1 and fot.fechaExpiracion > :fecha"
                . " ORDER BY fot.idargFoto DESC ";

        $fecha = new \DateTime('now');

        $promotions = $em->createQuery($dql)
                ->setParameter('posicion', $posicion)
                ->setParameter('fecha', $fecha)
                ->getResult();

        if (!empty($promotions)) {
            $max = count($promotions);

            if ($max > 20) {
                while ($i < 20) {
                    $random = rand(1, ($max - 1));

                    if (!in_array($random, $recuperados)) {
                        $recuperados[$i] = $random;
                        $prom[$i]['idargFoto'] = $promotions[$random]['idargFoto'];
                        $prom[$i]['src'] = $promotions[$random]['src'];
                        $i++;
                    }
                }
            } else {
                foreach ($promotions as $key => $value) {
                    $prom[$key]['idargFoto'] = $value['idargFoto'];
                    $prom[$key]['src'] = $value['src'];
                }
            }
        } else {
            $prom = NULL;
        }

        return $prom;
    }

}
