<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\CategoryType;
use App\Entity\Category;
use App\Entity\Exam;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class homeController extends AbstractController
{
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $listCategory = $entityManager->getRepository(Category::class)->findAll();
        if (null === $listCategory) {
            throw new NotFoundHttpException(
                'No Category found'
            );
        }
        return $this->render('Exam/index.html.twig', array(
            'listcategory' => $listCategory
        ));

        /*return $this->render('Exam/index.html.twig', array(
            'listcategory' => array()
        ));*/
    }
    public function add (Request $request)
    {
       /* $em = $this->getDoctrine()->getManager();
        // Création de l'entité
        $Category = new Category();
        $Category->setName('Recherche développeur Symfony.');
        $Category2 = new Category();
        $Category2->setName('Recherche développeur Symfony.');

        $em->persist($Category);
        $em->persist($Category2);
        $em->flush();
        return $this->render('Exam/index.html.twig', array(
            'listcategory' => array()
        ));*/
        $Exam = new Exam();
        $Category = new Category();
        //$formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $Exam);

        $form = $this->createFormBuilder($Exam)
            ->add('author', TextType::class)
            ->add('name', TextType::class)
            ->add('category', CategoryType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Exam'))
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
    
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
    
            return $this->redirectToRoute('home');
        }
        
        return $this->render('Exam/add.html.twig', array(
            'form' => $form->createView(),
        ));
     
    }

    public function addCategory (Request $request)
    {
        $Category = new Category();

        $form = $this->createFormBuilder($Category)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Category'))
            ->getForm();
            $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
    
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('Exam/addCategory.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function viewCategory ($Category)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $category = $entityManager->getRepository(Category::class)->find($Category);


        $examList = $entityManager
        ->getRepository(Exam::class)
        ->findBy(array('category' => $category));

        return $this->render('Exam/listCategory.html.twig', array(
        'examList' => $examList,
        'category' => $category
        ));

    }
}