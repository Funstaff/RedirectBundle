<?php

namespace Funstaff\Bundle\RedirectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Funstaff\Bundle\RedirectBundle\Entity\Redirect;
use Funstaff\Bundle\RedirectBundle\Form\Type\RedirectType;

/**
 * RedirectController.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectController extends Controller
{
    /**
     * Index
     */
    public function indexAction()
    {
        $records = $this->getRedirectRepository()->getAllOrderBySource()
                    ->getQuery()->getResult();

        return $this->render('FunstaffRedirectBundle:Redirect:index.html.twig', array(
            'records' => $records
        ));
    }

    /**
     * Add
     */
    public function addAction(Request $request)
    {
        $redirect = new Redirect();
        $form = $this->createForm($this->get('funstaff_redirect.redirect_type'), $redirect);

        if ('POST' === $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                $this->get('funstaff_redirect.redirect_manager')
                    ->save($form->getData());

                return $this->redirect($this->generateUrl('funstaff_redirect_index'));
            }
        }

        return $this->render('FunstaffRedirectBundle:Redirect:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit
     */
    public function editAction(Request $request, Redirect $redirect)
    {
        $form = $this->createForm($this->get('funstaff_redirect.redirect_type'), $redirect);

        if ('PUT' === $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                $this->get('funstaff_redirect.redirect_manager')
                    ->save($form->getData());

                return $this->redirect($this->generateUrl('funstaff_redirect_index'));
            }
        }

        return $this->render('FunstaffRedirectBundle:Redirect:edit.html.twig', array(
            'form' => $form->createView(),
            'formDelete' => $this->createDeleteForm($redirect->getId())->createView()
        ));
    }

    /**
     * Delete
     */
    public function deleteAction(Request $request, $id)
    {
        if ('DELETE' === $request->getMethod()) {
            $form = $this->createDeleteForm($id);
            $form->submit($request);
            if ($form->isValid()) {
                $this->get('funstaff_redirect.redirect_manager')
                    ->delete($id);
            }
        }

        return $this->redirect($this->generateUrl('funstaff_redirect_index'));
    }

    /**
     * Get Redirect Repository
     */
    private function getRedirectRepository()
    {
        return $this->getDoctrine()
                ->getRepository('FunstaffRedirectBundle:Redirect');
    }

    /**
     * Create Delete Form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}