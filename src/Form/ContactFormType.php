<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\ContactForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $priceRanges = [
            'A partir de 1000€' => '1000',
            'Entre 1000€ et 5000€' => '1000-5000',
            'Entre 5000€ et 10000€' => '5000-10000',
            'Plus de 10000€' => '10000+',
        ];

        $departments = [
            'Ain' => '01',
            'Aisne' => '02',
            'Allier' => '03',
            'Alpes-de-Haute-Provence' => '04',
            'Hautes-Alpes' => '05',
            'Alpes-Maritimes' => '06',
            'Ardèche' => '07',
            'Ardennes' => '08',
            'Ariège' => '09',
            'Aube' => '10',
            'Aude' => '11',
            'Aveyron' => '12',
            'Bouches-du-Rhône' => '13',
            'Calvados' => '14',
            'Cantal' => '15',
            'Charente' => '16',
            'Charente-Maritime' => '17',
            'Cher' => '18',
            'Corrèze' => '19',
            'Corse-du-Sud' => '2A',
            'Haute-Corse' => '2B',
            'Côte-d\'Or' => '21',
            'Côtes-d\'Armor' => '22',
            'Creuse' => '23',
            'Dordogne' => '24',
            'Doubs' => '25',
            'Drôme' => '26',
            'Eure' => '27',
            'Eure-et-Loir' => '28',
            'Finistère' => '29',
            'Gard' => '30',
            'Haute-Garonne' => '31',
            'Gers' => '32',
            'Gironde' => '33',
            'Hérault' => '34',
            'Ille-et-Vilaine' => '35',
            'Indre' => '36',
            'Indre-et-Loire' => '37',
            'Isère' => '38',
            'Jura' => '39',
            'Landes' => '40',
            'Loir-et-Cher' => '41',
            'Loire' => '42',
            'Haute-Loire' => '43',
            'Loire-Atlantique' => '44',
            'Loiret' => '45',
            'Lot' => '46',
            'Lot-et-Garonne' => '47',
            'Lozère' => '48',
            'Maine-et-Loire' => '49',
            'Manche' => '50',
            'Marne' => '51',
            'Haute-Marne' => '52',
            'Mayenne' => '53',
            'Meurthe-et-Moselle' => '54',
            'Meuse' => '55',
            'Morbihan' => '56',
            'Moselle' => '57',
            'Nièvre' => '58',
            'Nord' => '59',
            'Oise' => '60',
            'Orne' => '61',
            'Pas-de-Calais' => '62',
            'Puy-de-Dôme' => '63',
            'Pyrénées-Atlantiques' => '64',
            'Hautes-Pyrénées' => '65',
            'Pyrénées-Orientales' => '66',
            'Bas-Rhin' => '67',
            'Haut-Rhin' => '68',
            'Rhône' => '69',
            'Haute-Saône' => '70',
            'Saône-et-Loire' => '71',
            'Sarthe' => '72',
            'Savoie' => '73',
            'Haute-Savoie' => '74',
            'Paris' => '75',
            'Seine-Maritime' => '76',
            'Seine-et-Marne' => '77',
            'Yvelines' => '78',
            'Deux-Sèvres' => '79',
            'Somme' => '80',
            'Tarn' => '81',
            'Tarn-et-Garonne' => '82',
            'Var' => '83',
            'Vaucluse' => '84',
            'Vendée' => '85',
            'Vienne' => '86',
            'Haute-Vienne' => '87',
            'Vosges' => '88',
            'Yonne' => '89',
            'Territoire de Belfort' => '90',
            'Essonne' => '91',
            'Hauts-de-Seine' => '92',
            'Seine-Saint-Denis' => '93',
            'Val-de-Marne' => '94',
            'Val-d\'Oise' => '95',
        ];

        $surfaceRanges = [
            'Moins de 1 hectare' => '0-1',
            'Entre 1 et 5 hectares' => '1-5',
            'Entre 5 et 10 hectares' => '5-10',
            'Entre 10 et 20 hectares' => '10-20',
            'Entre 20 et 50 hectares' => '20-50',
            'Plus de 50 hectares' => '50+',
        ];

        $builder
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Email']])
            ->add('prix', ChoiceType::class, [
                'choices' => $priceRanges,
                'placeholder' => 'Prix',
            ])
            ->add('localisation', ChoiceType::class, [
                'choices' => $departments,
                'placeholder' => 'Département',
            ])
            ->add('surface', ChoiceType::class, [
                'choices' => $surfaceRanges,
                'placeholder' => 'Surface',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Catégorie',
            ])
            ->add('keyword', TextareaType::class, ['attr' => ['placeholder' => 'Mots clés']])
            ->add('save', SubmitType::class,['attr' => ['placeholder' => 'Valider']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactForm::class,
        ]);
    }
}
