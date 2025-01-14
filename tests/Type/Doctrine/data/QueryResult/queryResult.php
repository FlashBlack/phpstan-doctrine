<?php declare(strict_types = 1);

namespace QueryResult\queryResult;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use function PHPStan\Testing\assertType;

class QueryResultTest
{
	public function testQueryTypeParametersAreInfered(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType('Doctrine\ORM\Query<null, QueryResult\Entities\Many>', $query);

		$query = $em->createQuery('
			SELECT		m.intColumn, m.stringNullColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType('Doctrine\ORM\Query<null, array{intColumn: int, stringNullColumn: string|null}>', $query);

	}

	/**
	 * Test that we properly infer the return type of Query methods with implicit hydration mode
	 *
	 * - getResult() has a default hydration mode of HYDRATE_OBJECT, so we are able to infer the return type
	 * - Other methods have a default hydration mode of null and fallback on AbstractQuery::getHydrationMode(), so we can not assume the hydration mode and can not infer the return type
	 */
	public function testReturnTypeOfQueryMethodsWithImplicitHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<QueryResult\Entities\Many>',
			$query->getResult()
		);
		assertType(
			'iterable<int, QueryResult\Entities\Many>',
			$query->toIterable()
		);
		assertType(
			'mixed',
			$query->execute()
		);
		assertType(
			'mixed',
			$query->executeIgnoreQueryCache()
		);
		assertType(
			'mixed',
			$query->executeUsingQueryCache()
		);
		assertType(
			'mixed',
			$query->getSingleResult()
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult()
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_OBJECT
	 *
	 * We are able to infer the return type in most cases here
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitObjectHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<QueryResult\Entities\Many>',
			$query->getResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'iterable<int, QueryResult\Entities\Many>',
			$query->toIterable([], AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->execute(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'QueryResult\Entities\Many',
			$query->getSingleResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'QueryResult\Entities\Many|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn, m.stringNullColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->getResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->execute(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null}',
			$query->getSingleResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null}|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_ARRAY
	 *
	 * We can infer the return type by changing every object by an array
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitArrayHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<mixed>',
			$query->getResult(AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<mixed>',
			$query->getArrayResult()
		);
		assertType(
			'iterable<int, mixed>',
			$query->toIterable([], AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<mixed>',
			$query->execute(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<mixed>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<mixed>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn, m.stringNullColumn, m.datetimeColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}>',
			$query->getResult(AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}>',
			$query->getArrayResult()
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}>',
			$query->execute(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}',
			$query->getSingleResult(AbstractQuery::HYDRATE_ARRAY)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null, datetimeColumn: DateTime}|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_SCALAR
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitScalarHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<array>',
			$query->getResult(AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array>',
			$query->getScalarResult()
		);
		assertType(
			'iterable<int, array>',
			$query->toIterable([], AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array>',
			$query->execute(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'array',
			$query->getSingleResult(AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'array|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SCALAR)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn, m.stringNullColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->getResult(AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->getScalarResult()
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->execute(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'list<array{intColumn: int, stringNullColumn: string|null}>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null}',
			$query->getSingleResult(AbstractQuery::HYDRATE_SCALAR)
		);
		assertType(
			'array{intColumn: int, stringNullColumn: string|null}|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SCALAR)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_SCALAR
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitSingleScalarHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'mixed',
			$query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->getSingleScalarResult()
		);
		assertType(
			'iterable<int, mixed>',
			$query->toIterable([], AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->execute(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'int',
			$query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int',
			$query->getSingleScalarResult()
		);
		assertType(
			'int',
			$query->execute(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int',
			$query->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);

		$query = $em->createQuery('
			SELECT		COUNT(m.id)
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'int<0, max>|numeric-string',
			$query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int<0, max>|numeric-string',
			$query->getSingleScalarResult()
		);
		assertType(
			'int<0, max>|numeric-string',
			$query->execute(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int<0, max>|numeric-string',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int<0, max>|numeric-string',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int<0, max>|numeric-string',
			$query->getSingleResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
		assertType(
			'int<0, max>|numeric-string|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_SIMPLEOBJECT
	 *
	 * We are never able to infer the return type here
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitSimpleObjectHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<QueryResult\Entities\Many>',
			$query->getResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'iterable<int, QueryResult\Entities\Many>',
			$query->toIterable([], AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->execute(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<QueryResult\Entities\Many>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'QueryResult\Entities\Many',
			$query->getSingleResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'QueryResult\Entities\Many|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn, m.stringNullColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<mixed>',
			$query->getResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<mixed>',
			$query->execute(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<mixed>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'list<mixed>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SIMPLEOBJECT)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode of HYDRATE_SCALAR_COLUMN
	 *
	 * We are never able to infer the return type here
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitScalarColumnHydrationMode(EntityManagerInterface $em): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<mixed>',
			$query->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<mixed>',
			$query->getSingleColumnResult()
		);
		assertType(
			'iterable<int, mixed>',
			$query->toIterable([], AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<mixed>',
			$query->execute(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<mixed>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<mixed>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);

		$query = $em->createQuery('
			SELECT		m.intColumn
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'list<int>',
			$query->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<int>',
			$query->getSingleColumnResult()
		);
		assertType(
			'list<int>',
			$query->execute(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<int>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'list<int>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'int',
			$query->getSingleResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
		assertType(
			'int|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
		);
	}

	/**
	 * Test that we properly infer the return type of Query methods with explicit hydration mode that is not a constant value
	 *
	 * We are never able to infer the return type here
	 *
	 * @param int AbstractQuery::HYDRATE_*
	 */
	public function testReturnTypeOfQueryMethodsWithExplicitNonConstantHydrationMode(EntityManagerInterface $em, int $hydrationMode): void
	{
		$query = $em->createQuery('
			SELECT		m
			FROM		QueryResult\Entities\Many m
		');

		assertType(
			'mixed',
			$query->getResult($hydrationMode)
		);
		assertType(
			'iterable',
			$query->toIterable([], $hydrationMode)
		);
		assertType(
			'mixed',
			$query->execute(null, $hydrationMode)
		);
		assertType(
			'mixed',
			$query->executeIgnoreQueryCache(null, $hydrationMode)
		);
		assertType(
			'mixed',
			$query->executeUsingQueryCache(null, $hydrationMode)
		);
		assertType(
			'mixed',
			$query->getSingleResult($hydrationMode)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult($hydrationMode)
		);
	}

	/**
	 * Test that we return the original return type when ResultType may be
	 * VoidType
	 *
	 * @param Query<mixed> $query
	 */
	public function testReturnTypeOfQueryMethodsWithReturnTypeIsMixed(EntityManagerInterface $em, Query $query): void
	{
		assertType(
			'mixed',
			$query->getResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'iterable',
			$query->toIterable([], AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->execute(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
		);
	}

	/**
	 * Test that we return the original return type when ResultType may be
	 * VoidType (TemplateType variant)
	 *
	 * @template T
	 *
	 * @param Query<T> $query
	 */
	public function testReturnTypeOfQueryMethodsWithReturnTypeIsTemplateMixedType(EntityManagerInterface $em, Query $query): void
	{
		assertType(
			'mixed',
			$query->getResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'iterable',
			$query->toIterable([], AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->execute(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->getSingleResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'mixed',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
		);
	}


	/**
	 * Test that we return ResultType return ResultType can not be VoidType
	 *
	 * @template T of array|object
	 *
	 * @param Query<T> $query
	 */
	public function testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(EntityManagerInterface $em, Query $query): void
	{
		assertType(
			'list<T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)>',
			$query->getResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'iterable<int, T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)>',
			$query->toIterable([], AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)>',
			$query->execute(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)>',
			$query->executeIgnoreQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'list<T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)>',
			$query->executeUsingQueryCache(null, AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument)',
			$query->getSingleResult(AbstractQuery::HYDRATE_OBJECT)
		);
		assertType(
			'(T of array|object (method QueryResult\queryResult\QueryResultTest::testReturnTypeOfQueryMethodsWithReturnTypeIsNonVoidTemplate(), argument))|null',
			$query->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
		);
	}
}
