<template>
	<div class="dialog-wrapper">
		<v-dialog class="steps-dialog" max-width="920" v-model="dialog" dialog-transition="true" :persistent="true" content-class="dialog-steps">
			<template v-if="!initState">
				<div class="welcomeWin welcomeWin--init">
					<div class="welcomeWin-caption">
						Идет инициализация программы
					</div>
					<div class="welcomeWin-slider">
						<div class="welcomeWin-slider-wrapper">
							<hooper
								style="height: 75px"
								:vertical="true"
								:autoPlay="true"
								:itemsToShow="1.5"
								:infiniteScroll="true"
								:centerMode="true"
								:trimWhiteSpace="true"
								:wheelControl="false"
								:keysControl="false"
								:shortDrag="false"
							>
								<slide v-for="(val, index) in sliderItems" :key="index">
									{{ val }}
								</slide>
							</hooper>
						</div>
					</div>
					<div class="welcomeWin-loader">
						<div class="welcomeWin-loader-item"></div>
						<div class="welcomeWin-loader-item"></div>
						<div class="welcomeWin-loader-item"></div>
					</div>
				</div>
			</template>
			<template v-else>
				<transition name="fade">
					<!-- step 1 -->
					<div class="welcomeWin welcomeWin--sales welcomeWin--step1" v-if="currentStep === 1">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Добро пожаловать!</div>
							<div class="welcomeWinTopText__subtitle">Для завершения регистрации и дальнейшей работы, пожалуйста, заполните информацию о вашей компании.</div>
						</div>
						<div :class="{'welcomeWinLive': true, 'welcomeWinLive--active': welcomeWinItems}">
							<div class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask" v-if="stepData[computedStep - 1]">{{ stepData[computedStep - 1].title }}</div>
							</div>
						</div>
						<div class="welcomeWinBottom">

							<div class="welcomeWinPick welcomeWinPick--sales welcomeWinPick--step1">

								<template v-if="stepData[computedStep - 1]">
									<div v-for="(val, index) in stepData[computedStep - 1].items" :key="index" class="welcomeWinPick__item">
										<input type="checkbox" name="sales" :id="val.id" @click="changeField($event, 'first')" :value="val.title" :data-index="index" :checked="checkedItems['firstStep'][val.id] ? true : false">
										<label :for="val.id" class="welcomeWinPick__label">{{ val.title }}</label>
									</div>
								</template>

							</div>

							<div class="welcomeWinNav">
								<button :class="{ 'welcomeWinNav__next' : true, 'welcomeWinNav__next--disabled' : Object.keys(checkedLength['firstStep']).length === 0}" @click="next('first')">Вперед</button>
							</div>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 1 end -->

					<!-- step 2 -->
					<div class="welcomeWin welcomeWin--customerCount welcomeWin--step2" v-if="currentStep === 2">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Добро пожаловать!</div>
							<div class="welcomeWinTopText__subtitle">Для завершения регистрации и дальнейшей работы, пожалуйста, заполните информацию о вашей компании.</div>
						</div>

						<div :class="{'welcomeWinLive': true, 'welcomeWinLive--active': welcomeWinItems}">
							<div v-for="(val, key) in historyItems" :key="key" class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ val.title }}</div>
								<div class="welcomeWinLive__ans">
									{{ val.items }}
								</div>
							</div>
							<div class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ steps.data[currentStep - 1].title }}</div>
							</div>
						</div>
						<div class="welcomeWinBottom">

							<div class="welcomeWinPick welcomeWinPick--customerCount welcomeWinPick--step2">

								<div v-for="(val, index) in steps.data[currentStep - 1].items" :key="index" class="welcomeWinPick__item">
									<input type="radio" name="customerCount" @click="changeField($event, 'second')" :id="val.id" :value="val.title" :checked="checkedItems['secondStep'][val.id] ? true : false">
									<label :for="val.id" class="welcomeWinPick__label welcomeWinPick__label--bold">{{ val.title }}</label>
								</div>

							</div>

							<div class="welcomeWinNav">
								<button class="welcomeWinNav__prev" @click="prev">Назад</button>
								<button :class="{ 'welcomeWinNav__next' : true, 'welcomeWinNav__next--disabled' : Object.keys(checkedLength['secondStep']).length === 0 }" @click="next('second')">Вперед</button>
							</div>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 2 end -->

					<!-- step 3 -->
					<div class="welcomeWin welcomeWin--automatization welcomeWin--step3" v-if="currentStep === 3">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Добро пожаловать!</div>
							<div class="welcomeWinTopText__subtitle">Для завершения регистрации и дальнейшей работы, пожалуйста, заполните информацию о вашей компании.</div>
						</div>
						<div :class="{'welcomeWinLive': true, 'welcomeWinLive--shadowTop': true, 'welcomeWinLive--active': welcomeWinItems}">
							<div v-for="(val, key) in historyItems" :key="key" class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ val.title }}</div>
								<div class="welcomeWinLive__ans">
									{{ val.items }}
								</div>
							</div>
							<div class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ steps.data[currentStep - 1].title }}</div>
							</div>
						</div>
						<div class="welcomeWinBottom">

							<div class="welcomeWinPick welcomeWinPick--automatization welcomeWinPick--step3">

								<div v-for="(val, index) in steps.data[currentStep - 1].items" :key="index" class="welcomeWinPick__item">
									<input type="checkbox" name="automatization" @click="changeField($event, 'third')" :id="val.id" :value="val.title" :checked="checkedItems['thirdStep'][val.id] ? true : false">
									<label :for="val.id" class="welcomeWinPick__label">
										<span class="welcomeWinPick__shortName">{{ val.title.split('-')[0] }}</span> {{ val.title.split('-')[1] && '-' + val.title.split('-')[1] }}
									</label>
								</div>

							</div>

							<div class="welcomeWinNav">
								<button class="welcomeWinNav__prev" @click="prev">Назад</button>
								<button :class="{ 'welcomeWinNav__next' : true, 'welcomeWinNav__next--disabled' : Object.keys(checkedLength['thirdStep']).length === 0}" @click="next('third')">Вперед</button>
							</div>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 3 end -->

					<!-- step 4 -->
					<div class="welcomeWin welcomeWin--integration welcomeWin--step4" v-if="currentStep === 4">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Добро пожаловать!</div>
							<div class="welcomeWinTopText__subtitle">Для завершения регистрации и дальнейшей работы, пожалуйста, заполните информацию о вашей компании.</div>
						</div>
						<div :class="{'welcomeWinLive': true, 'welcomeWinLive--shadowTop': true, 'welcomeWinLive--active': welcomeWinItems}">
							<div v-for="(val, key) in historyItems" :key="key" class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ val.title }}</div>
								<div class="welcomeWinLive__ans">
									{{ val.items }}
								</div>
							</div>
							<div class="welcomeWinLive__item">
								<div class="welcomeWinLive__ask">{{ steps.data[currentStep - 1].title }}</div>
							</div>
						</div>
						<div class="welcomeWinBottom">

							<div class="welcomeWinPick welcomeWinPick--integration welcomeWinPick--step4">

								<div v-for="(val, index) in steps.data[currentStep - 1].items" :key="index" class="welcomeWinPick__item">
									<input type="checkbox" name="integration" @click="changeField($event, 'four')" :id="val.id" :value="val.title" :checked="checkedItems['fourStep'][val.id] ? true : false">
									<label :for="val.id" class="welcomeWinPick__label">{{ val.title }}</label>
								</div>

							</div>

							<div class="welcomeWinNav">
								<button class="welcomeWinNav__prev" @click="prev">Назад</button>
								<button :class="{ 'welcomeWinNav__next' : true, 'welcomeWinNav__next--disabled' : Object.keys(checkedLength['fourStep']).length === 0 }" @click="next('four')">Вперед</button>
							</div>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 4 end -->

					<!-- step 5 choose rate -->
					<div class="welcomeWin welcomeWin--chooseRate welcomeWin--step5" v-if="currentStep === 5">
						<div class="welcomeWinTopText welcomeWinTopText--chooseRate">
							<div class="welcomeWinTopText__title">Отлично!</div>
							<div class="welcomeWinTopText__subtitle welcomeWinTopText__subtitle--chooseRate" v-show="selectedTarrif.tariffId === null">На основании ваших ответов, мы рекомендуем вам тариф <span class="welcomeWinTopText__relatedRate">{{ recommendTarrif }} :</span></div>
							<div class="welcomeWinTopText__subtitle welcomeWinTopText__subtitle--selectedRate" v-show="selectedTarrif.tariffId !== null">Вы выбрали тариф <span class="welcomeWinTopText__relatedRate">{{ selectedTarrif.tarrifTitle }} :</span></div>
						</div>
						<div class="welcomeWinRates myScroll">

							<!-- selected модификатор активирует шапку выбранного тарифа -->
							<!-- related модификатор активирует шапку рекомендуемого тарифа -->

							<div v-for="(item, index) in responseTarrifItems.data" :key="index" :class="{ 'welcomeWinRates__rate': true, 'welcomeWinRates__rate--related': item.isRecommend && selectedTarrif.tariffId === null, 'welcomeWinRates__rate--selected': selectedTarrif.tariffId === item.id }" @click="selectTarrif($event, item.id, item.title, item.cost)" :data-tariff-id="item.id">
								<div class="welcomeWinRates__header welcomeWinRates__header--selected">Выбранный</div>
								<div class="welcomeWinRates__header welcomeWinRates__header--related">Рекомендуемый</div>
								<div class="welcomeWinRates__name">{{ item.title }}</div>
								<div class="welcomeWinRates__price">
									<div class="welcomeWinRates__cost">{{ item.cost }}</div>
									<div class="welcomeWinRates__currency">грн/мес</div>
								</div>
								<div class="welcomeWinRates__includes">
									<ul>
										<li v-for="(val, key) in item.custom_column" :key="key" :data-id="val.id" :data-order="val.order" :class="{ 'not-active': val.active == true ? true : false }">{{ val.title }}</li>
									</ul>
								</div>
							</div>

						</div>
						<div class="welcomeWinNav welcomeWinNav--chooseRate">
							<!-- <a href="#" class="welcomeWinNav__link welcomeWinNav__link--noUnd">Бесплатная пробная версия к любому тарифу - 30 дней!</a> -->
							<button class="welcomeWinNav__prev" @click="prev">Назад</button>
							<button :class="{ 'welcomeWinNav__next': true,  'welcomeWinNav__next--payment': true, 'welcomeWinNav__next--disabled': selectedTarrif.tariffId !== null ? false : true }" @click="nextPayment">Перейти к оплате</button>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 5 end -->

					<!-- step 6 choose payment method -->
					<div class="welcomeWin welcomeWin--choosePaymentMethod welcomeWin--step6" v-if="currentStep === 6">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Выбран тариф <span class="welcomeWinTopText__selectedRate">{{ selectedTarrif.tarrifTitle }}</span></div>
							<div class="welcomeWinTopText__subtitle welcomeWinTopText__subtitle--bold">Выберите варианты оплаты:</div>
						</div>
						<div class="welcomeWinPaymentMethods">
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_card_pay">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_card_pay">
									<img src="@/assets/img/card_pay.svg">
									<span>Оплата картой</span>
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_apple_pay">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_apple_pay">
									<img src="@/assets/img/apple_pay.svg">
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_google_pay">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_google_pay">
									<img src="@/assets/img/google_pay.svg">
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_liqpay" @change="liqpayPayment" :value="{liqpay: 'liqpay'}" v-model="payment.methods">
								<label class="welcomeWinPaymentMethods__item" for="paymentMethods_liqpay">
									<img src="@/assets/img/liqpay.svg">
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_privat" :value="{privat: 'pay'}" v-model="payment.methods">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_privat">
									<img src="@/assets/img/privat.svg">
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_webmoney">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_webmoney">
									<img src="@/assets/img/webmoney.svg">
								</label>
							</div>
							<div class="welcomeWinPaymentMethods__itemWrap">
								<input type="radio" name="paymentMethods" id="paymentMethods_easy_pay">
								<label class="welcomeWinPaymentMethods__item disabled" for="paymentMethods_easy_pay">
									<img src="@/assets/img/easy_pay.png">
								</label>
							</div>
						</div>
						<div class="welcomeWinPrint">
							<a href="#" class="welcomeWinPrint__toPrint">Распечатать счет на оплату</a>
						</div>
						<div class="welcomeWinNav welcomeWinNav--choosePaymentMethod">
							<a href="#" class="welcomeWinNav__link" @click.prevent="paymentTrial">Перейти к 30-дневной бесплатной пробной версии</a>
							<button class="welcomeWinNav__prev" @click="prev">Назад</button>
							<button :class="{'welcomeWinNav__next' : true, 'welcomeWinNav__next--payment': true, 'welcomeWinNav__next--disabled': payment.status === null}" @click="successPayment">Оплатить</button>
						</div>
						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 6 end -->

					<!-- step 7 -->
					<div class="welcomeWin welcomeWin--createPass welcomeWin--step7" v-if="currentStep === 7">
						<div class="welcomeWinTopText">
							<div class="welcomeWinTopText__title">Новый пароль</div>
						</div>
						<div class="welcomeWinCreatePass">
							<form action="#" method="GET" onsubmit="return false">

							<!--	--complete если указали пароль с верными условиями - текст зеленый + иконка

								--repeatYes строка с сообщенькой об совпадении паролей
								--repeatNo строка с сообщенькой о том что пароли не совпали
								--disable одна из строк скрыта

								toggle--close - иконка закрытый глаз (показать пароль)
								toggle--open - иконка открытый глаз (скрыть пароль)

								readonly  - костыль на отключение автозаполнения

							-->

								<div class="welcomeWinCreatePassRow">
									<div class="welcomeWinCreatePassRow__enter">
										<label class="formLabel" for="createPass_enter">Введите пароль</label>
										<input class="formInput" :type="typePass" @keyup="checkNumAndWord" v-model="password" name="password" id="createPass_enter" readonly="readonly" onfocus="this.removeAttribute('readonly');">
										<button :class="{'welcomeWinCreatePassRow__toggle': true,  'welcomeWinCreatePassRow__toggle--open': !showPass, 'welcomeWinCreatePassRow__toggle--close': showPass}" @click="showPassword($event)" v-if="password.length" data-type="password"></button>
									</div>
									<div :class="{ 'welcomeWinCreatePassRow__desc': true, 'focused': password.length }">
										<div class="welcomeWinCreatePassRow__title">Создайте пароль, который:</div>
										<div :class="{'welcomeWinCreatePassRow__str': true, 'welcomeWinCreatePassRow__str--complete': $v.password.minLength }">Содержит минимум 8 символов</div> <!-- Success class: welcomeWinCreatePassRow__str--complete -->
										<div :class="{'welcomeWinCreatePassRow__str': true, 'welcomeWinCreatePassRow__str--complete': checkPassValidate }">Содержит хотя бы одну цифру и букву</div>
										<div :class="{'welcomeWinCreatePassRow__str': true, 'welcomeWinCreatePassRow__str--complete': checkLatinPass }">Содержит только латинские буквы</div>
									</div>
								</div>

								<div class="welcomeWinCreatePassRow welcomeWinCreatePassRow--repeat">
									<div class="welcomeWinCreatePassRow__enter">
										<label class="formLabel" for="createPass_enter">Повторите пароль</label>
										<input class="formInput" :type="typePassRepeat" v-model="password_repeat" name="password_repeat" id="createPass_repeat">
										<button :class="{'welcomeWinCreatePassRow__toggle': true, 'welcomeWinCreatePassRow__toggle--open': !showPassRepeat, 'welcomeWinCreatePassRow__toggle--close': showPassRepeat}" v-if="password_repeat.length" @click="showPassword($event)" data-type="password_repeat"></button>
									</div>
									<div :class="{ 'welcomeWinCreatePassRow__desc': true, 'focused': password_repeat.length }">
										<div class="welcomeWinCreatePassRow__str welcomeWinCreatePassRow__str--repeatYes" v-show="$v.password_repeat.sameAsPassword">Пароли совпадают :)</div>
										<!-- Disabled class welcomeWinCreatePassRow__str--disable -->
										<div class="welcomeWinCreatePassRow__str welcomeWinCreatePassRow__str--repeatNo" v-show="!$v.password_repeat.sameAsPassword">Пароли не совпадают :(</div>
									</div>
								</div>

								<div class="welcomeWinBottom welcomeWinBottom--createPass">
									<div class="welcomeWinNav">
										<button :class="{'welcomeWinNav__next': true, 'welcomeWinNav__next--payment': true, 'welcomeWinNav__next--disabled': !$v.password_repeat.sameAsPassword || checkPassValidate == false || !$v.password.minLength || !checkLatinPass }" @click="successPassword">Сохранить и продолжить</button>
									</div>
								</div>

							</form>
						</div>

						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 7 end -->

					<!-- step 8 -->
					<div class="welcomeWin welcomeWin--personal welcomeWin--step8" v-if="currentStep === 8">
						<div class="welcomeWinTopText welcomeWinTopText--personal">
							<div class="welcomeWinTopText__title">Немного информации для начала успешной работы:</div>
						</div>
						<div class="welcomeWinPersonal">
							<form action="#" method="POST" @submit="editSubmitForm">

						<!--	--required звездочка в label
								--valid зеленая обводка + иконка
								--invalid оранжевая обводка + иконка -->
						<!-- welcomeWinPersonal__field--valid, welcomeWinPersonal__field--invalid  -->

								<div class="welcomeWinPersonal__row">
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editCompanyValid, 'welcomeWinPersonal__field--invalid': editCompanyInvalid}">
										<label class="formLabel" for="personal_organization">Название вашей организации</label>
										<input class="formInput" type="text" id="personal_organization" name="company_name" placeholder="Apricode" @blur="setNameCompany" :value="editData.company_name !== null ? editData.company_name : null">
									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editDomainValid, 'welcomeWinPersonal__field--invalid': editDomainInvalid }">
										<label class="formLabel" for="personal_site-addr">Адрес сайта</label>

										<input
											class="formInput"
											type="text"
											id="personal_site-addr"
											@focus="setDomainName"
											@input="setDomainName"
											@blur="blurDomainName"
											name="domain"
											placeholder="apricode"
											:value="editNewData.domain.split('.')[0] !== null ? editNewData.domain.split('.')[0] : null"
										>
										<span class="welcomeWinPersonal__subtext">{{ editData.main_domain }}</span>
									</div>
								</div>

								<div class="welcomeWinPersonal__row" :class="{'welcomeWinPersonal__field--disabled': isPhone }">
									<!-- :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--valid': phoneValid && editNewData.phone !== null && editNewData.phone !== '', 'welcomeWinPersonal__field--invalid': phoneInvalid && editNewData.phone !== null && editNewData.phone !== '', 'welcomeWinPersonal__field--disabled': isPhone }" -->
									<div style="width: 330px;">
										<label class="formLabel" for="personal_phone" v-if="!isPhone">Номер телефона</label>
										<label class="formLabel" for="personal_phone" v-else>Номер телефона авторизации</label>
										<!-- <input class="formInput" type="text" id="personal_phone" name="phone" @blur="checkPhoneValidate" @input="setPhone" :value="editNewData.phone !== null ? editNewData.phone : null" placeholder="+38 (0__) ___ __ __" v-mask="'+## (###) ### ## ##'"> -->
										<vue-phone
											v-bind="phoneSettings"
											:valueField="editNewData.phone !== null ? editNewData.phone : ''"
											:isValidFlag="phoneValid"
											phoneLabel="Номер телефона"
											@updateInput="inputHandler"
											@blurInput="inputBlurHandler"
										>
										</vue-phone>
										<!-- :disabled="isPhone" -->

									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--valid': $v.editNewData.email.$invalid === false && editNewData.email !== null && editNewData.email !== '', 'welcomeWinPersonal__field--invalid': $v.editNewData.email.$invalid === true && editNewData.email !== null && editNewData.email !== '', 'welcomeWinPersonal__field--disabled': isEmail }">
										<label class="formLabel" for="personal_email" v-if="!isEmail">E-mail</label>
										<label class="formLabel" for="personal_email" v-else>E-mail авторизации</label>
										<input class="formInput" type="email" id="personal_email" name="email" placeholder="E-mail" @input="setEmail" :value="editData.email !== null ? editData.email : null">
									</div>
								</div>

								<div class="welcomeWinPersonal__row welcomeWinPersonal__row--self">
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editNewData.last_name !== null && editNewData.last_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': editNewData.last_name !== null && editNewData.last_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_lastName">Фамилия</label>
										<input class="formInput" type="text" id="personal_lastName" name="last_name" placeholder="Фамилия" @blur="setFields" :value="editData.last_name !== null ? editData.last_name : null">
									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editNewData.first_name !== null && editNewData.first_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': editNewData.first_name !== null && editNewData.first_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_name">Имя</label>
										<input class="formInput" type="text" id="personal_name" name="first_name" placeholder="Имя" @blur="setFields" :value="editData.first_name !== null ? editData.first_name : null">
									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editNewData.middle_name !== null && editNewData.middle_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': editNewData.middle_name !== null && editNewData.middle_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_middleName">Отчество</label>
										<input class="formInput" type="text" id="personal_middleName" name="middle_name" placeholder="Отчество" @blur="setFields" :value="editData.middle_name !== null ? editData.middle_name : null">
									</div>
								</div>

								<div class="welcomeWinPersonal__row welcomeWinPersonal__field--required">
									<!--  welcomeWinPersonal__field--required, welcomeWinPersonal__field--select -->
									<div class="welcomeWinPersonal__field">
										<label :class="{'formLabelDisabled': disabledSelect}" class="formLabel" for="personal_position">Ваша должность в организации</label>
										<!-- <select class="formInput" name="personal_position" id="personal_position">
											<option value="personal_position_1">Директор</option>
											<option value="personal_position_2">Менеджер</option>
											<option value="personal_position_3">Кладовщик</option>
										</select> -->
										<template v-if="positionsList">
											<v-select
												:disabled="disabledSelect"
												:class="{'select-switcher': true, 'welcomeWinPersonal__field--valid': editSelect}"
												item-text="title"
												item-value="id"
												label="Выберите должность"
												:items="positionsList"
												@change="changeSelect"
												:menu-props="{ contentClass: 'select-positions-list'}"
												return-object
											>
											</v-select>
										</template>
									</div>
								</div>

								<div class="welcomeWinBottom welcomeWinBottom--personal">
									<div class="welcomeWinNav">
										<!-- <button class="welcomeWinNav__prev">Назад</button> -->
										<button 
											type="submit" 
											:class="{'welcomeWinNav__next': true, 'welcomeWinNav__next--payment': true, 'welcomeWinNav__next--disabled': $v.editNewData.$invalid || !phoneValid || editEmailInvalid || disabledCompleteButton || editDomainInvalid }"
										>
											Готово! Вперед
										</button>
									</div>
								</div>
							</form>
						</div>

						<div class="welcomeWin__banner">
							<img src="@/assets/img/logo3.svg" alt="">
						</div>
					</div>
					<!-- step 8 end -->

					<!-- Step for users -->

					<div class="welcomeWin welcomeWin--users" v-if="currentStep === 9">

						<div class="welcomeWinTopText welcomeWinTopText--users">
							<div class="welcomeWinTopText__title">Добро пожаловать!</div>
							<div class="welcomeWinTopText__subtitle">Немного информации для начала успешной работы:</div>
						</div>

						<div class="welcomeWinPersonal">
							<div class="wrapper">

								<div class="welcomeWinPersonal__row">
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': phoneValid, 'welcomeWinPersonal__field--invalid': phoneInvalid }">
										<label class="formLabel" for="personal_phone">Номер телефона</label>
										<input @blur="checkPhoneValidateUser" class="formInput" type="text" id="personal_phone" name="phone" placeholder="+38 (0__) ___ __ __" v-mask="'+## (###) ### ## ##'" v-model="userData.phone">
									</div>
									<div :class="{'welcomeWinPersonal__field': true,  'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': editEmailValid, 'welcomeWinPersonal__field--invalid': editEmailInvalid }">
										<label class="formLabel" for="personal_email">E-mail</label>
										<input @blur="setEmailUser" class="formInput" type="email" id="personal_email" name="email" placeholder="E-mail" v-model="userData.email">
									</div>
								</div>

								<div class="welcomeWinPersonal__row welcomeWinPersonal__row--self">
									<div  :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': userData.last_name !== null && userData.last_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': userData.last_name !== null && userData.last_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_lastName">Фамилия</label>
										<input class="formInput" type="text" id="personal_lastName" name="last_name" placeholder="Фамилия" v-model="userData.last_name">
									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': userData.first_name !== null && userData.first_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': userData.first_name !== null && userData.first_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_name">Имя</label>
										<input class="formInput" type="text" id="personal_name" name="first_name" placeholder="Имя" v-model="userData.first_name">
									</div>
									<div :class="{'welcomeWinPersonal__field': true, 'welcomeWinPersonal__field--required': true, 'welcomeWinPersonal__field--valid': userData.middle_name !== null && userData.middle_name.length > 0 ? true : false, 'welcomeWinPersonal__field--invalid': userData.middle_name !== null && userData.middle_name.length < 1 ? true : false }">
										<label class="formLabel" for="personal_middleName">Отчество</label>
										<input class="formInput" type="text" id="personal_middleName" name="middle_name" placeholder="Отчество" v-model="userData.middle_name">
									</div>
								</div>

								<div class="welcomeWinPersonal__row">
									<div class="welcomeWinPersonal__field">
										<label class="formLabel" for="createPass_enter">Введите пароль</label>
										<input class="formInput" :type="typePass" @keyup="checkNumAndWordUser" name="password" v-model="userData.password">
										<button
											:class="{'welcomeWinCreatePassRow__toggle': true, 'welcomeWinCreatePassRow__toggle--open': !showPass, 'welcomeWinCreatePassRow__toggle--close': showPass}"
											@click="showPassword($event)"
											v-if="userData.password.length"
											data-type="password">
										</button>
										<div class="password-popup" :class="{'password-popup--open': userData.password.length}">
											<div class="password-popup__title">Создайте пароль, который:</div>
											<div class="password-popup__list">
												<div class="list-item" :class="{'list-item--success': $v.userData.password.minLength}">Содержит минимум 8 символов</div>
												<div class="list-item" :class="{'list-item--success': checkPassValidate}">Содержит хотя бы одну цифру и букву</div>
												<div class="list-item" :class="{'list-item--success': checkLatinPass}">Содержит только латинские буквы</div>
											</div>
										</div>
									</div>
									<div class="welcomeWinPersonal__field">
										<label class="formLabel" for="createPass_enter">Повторите пароль</label>
										<input class="formInput" :type="typePassRepeat" name="password_repeat" v-model="userData.password_repeat">
										<button
											:class="{'welcomeWinCreatePassRow__toggle': true, 'welcomeWinCreatePassRow__toggle--open': !showPassRepeat, 'welcomeWinCreatePassRow__toggle--close': showPassRepeat}"
											v-if="userData.password_repeat.length"
											@click="showPassword($event)"
											data-type="password_repeat">
										</button>
										<div class="password-popup" :class="{'password-popup--open': userData.password_repeat.length}">
											<div class="password-popup__list">
												<div class="list-item list-item--complete" v-show="$v.userData.password_repeat.sameAsPassword">Пароли совпадают :)</div>
												<div class="list-item" v-show="!$v.userData.password_repeat.sameAsPassword">Пароли не совпадают :(</div>
											</div>
										</div>
									</div>
								</div>

								<div class="welcomeWinBottom welcomeWinBottom--personal">
									<div class="welcomeWinNav">
										<button
											@click="completeUsers"
											type="button"
											:class="{'welcomeWinNav__next': true, 'welcomeWinNav__next--payment': true, 'welcomeWinNav__next--disabled': $v.userData.$invalid || phoneInvalid || editEmailInvalid }"
										>
											Готово! Вперед
										</button>
									</div>
								</div>

							</div>
						</div>

					</div>

					<!-- End step for users -->
				</transition>
			</template>
		</v-dialog>
	</div>
</template>

<script>
    import { Hooper, Slide } from 'hooper'
	import axios from "axios"
	import Liqpay from 'liq-sdk/liqpay/lib/liqpay'
	// import MaskedInput from 'vue-masked-input'
	// import liqpayCheckout from '../../plugins/liqpay-checkout'
	import { validationMixin } from 'vuelidate'
	import { required, minLength, sameAs, email } from 'vuelidate/lib/validators'
	import { mapGetters } from 'vuex'
	import debounce from 'lodash.debounce'
    import VuePhone from '@/components/ui/VuePhone/VuePhone'
    import {MAIN_DOMAIN} from "@/domain"
	import { PROTOCOL } from '@/domain'

	export default {
		name: "Steps",
		mixins: [validationMixin],
		validations: {
			password: {
				minLength: minLength(8)
			},
			password_repeat: {
				sameAsPassword: sameAs('password')
			},
			editNewData: {
				company_name: { required },
				domain: { required },
				first_name: { required },
				last_name: { required },
				middle_name: { required },
				position_id: { required },
				email: { email }
			},
			userData: {
				password: {
					minLength: minLength(8),
					required
				},
				password_repeat: {
					sameAsPassword: sameAs('password'),
					required
				},
				email: { email, required },
				first_name: { required },
				last_name: { required },
				middle_name: { required },
				phone: { required },
			}
		},
		components: {
			// MaskedInput
			Hooper,
			Slide,
			VuePhone
		},
		data: () => ({
			mask: ['+', /\d/, /\d/, ' (', /\d/, /\d/, /\d/, ') ', /\d/, /\d/, /\d/, ' ', /\d/, /\d/, ' ', /\d/, /\d/],
			initState: true,
			dialog: true,
			currentStep: 1,
			protocolMethod: PROTOCOL,
			selectedTarrif: {
				tariffId: null,
				tarrifTitle: null,
				tarrifCost: null,
			},
			welcomeWinItems: false,
			recommendTarrif: null,
			responseTarrifItems: {},
			dataTarrif: {
				steps: []
			},
			payment: {
				trial: null,
				provider: null,
				status: null,
				checkStatus: false,
				methods: {},
			},
			historyItems: [],
			domain: JSON.parse(localStorage.getItem('domain')),
			checkedFields: [],
            checkedItems: {
                firstStep: {},
                secondStep: {},
                thirdStep: {},
                fourStep: {}
            },
			steps: {
				data: []
			},
			disabledSelect: true,
			disabledCompleteButton: false,
			// Password setting
			showPass: false,
			showPassRepeat: false,
			typePass: 'password',
			typePassRepeat: 'password',
			password: '',
			password_repeat: '',
			checkPassValidate: false,
			passSuccess: false,
			checkLatinPass: false,
			// Edit settings
			defaultPosition: null,
			positions: null,
			editData: null,
			editSelect: false,
			editEmailValid: false,
			editEmailInvalid: false,
			editCompanyValid: false,
			editCompanyInvalid: false,
			editDomainValid: false,
			editDomainInvalid: false,
			phoneValid: false,
			phoneInvalid: false,
			domainName: '',
			editNewData: {},
			checkCompleteEdit: false,
			// liqpay settings
			liqPaySettings: {
				publicKey: 'sandbox_i47561436603',
				privateKey: 'sandbox_q7d7tijOPcWKNK0qEaF4JqgEzWof4y3nPSrg7STK',
				data: null,
				signature: null
			},
			sliderItems: [
				'Подготовка к работе',
				'Создается личный кабинет',
				'Добавляется первый пользователь'
			],
			userData: {
				email: null,
				phone: null,
				last_name: null,
				first_name: null,
				middle_name: null,
				password: '',
				password_repeat: ''
			},
			isPhone: false,
			isEmail: false,
			phoneSettings: {
				defaultCountry: "UA",    
				preferredCountries: ['UA', 'RU'],
				customPhoneClass: ['phone-step'],
			}
		}),
		methods: { 
			inputHandler(val, flag) {
				console.log('inputHandler', val, flag)
				this.editData = {...this.editData, phone: val}
				this.editNewData = {...this.editNewData, phone: val}
				this.phoneValid = flag
			},
			inputBlurHandler(val) {
				console.log('blur', val)
				this.checkPhoneValidate(val)
			},
			next(step) {
				if(Object.keys(this.checkedLength[`${step}Step`]).length > 0) {
					let typeItems = Object.values(this.checkedItems[`${step}Step`]).join(", ")
					let checkedIdx = Object.keys(this.checkedItems[`${step}Step`]).map(item => ({ "id" : item }) )
					this.historyItems.push({ title: this.steps.data[this.currentStep - 1].title , items: typeItems })
					this.dataTarrif.steps.push({ step_number: this.currentStep, items: checkedIdx })
					if(step === 'four') {
						this.getTarrifs()
						this.currentStep++
					} else {
						this.currentStep++
					}
				}
			},
			nextPayment() {
				if(this.selectedTarrif.tariffId !== null) {
					this.generateLiqpayData(this.liqPaySettings.publicKey, this.liqPaySettings.privateKey, this.selectedTarrif.tarrifCost)
					this.currentStep++
				}
			},
			successPayment() {
				const isUserSocial = this.$store.getters.user.social
				const self = this
				console.log('isUserSocial', isUserSocial)
				if(this.payment.status !== null && this.payment.checkStatus !== null) {
					console.log('isUserSocial', isUserSocial)
					if(!isUserSocial) {
						console.log('user false')
						self.editDataHundler()
						self.currentStep++
					} else {
						console.log('user true')
						self.currentStep = self.currentStep + 2
						self.editDataHundler()
						self.checkEditData(self.editNewData, self)
					}
				}
			},
			async successPasswordUsers() {
				const token = this.$store.getters.token
				const initialData = {
					data: {
						password: this.userData.password
					}
				}
				const { data: { success }, status } = await axios.post(`${this.protocolMethod}://${this.domain}/api/v1/user/password/change`, initialData, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/user/password/change, received ${status}`)
				} else {
					console.log(success)
					// this.passSuccess = success
				}
			},
			async successPassword() {
				// 12345678g
				const token = this.$store.getters.token
				// // const self = this
				const initialData = {
					data: {
						password: this.password
					}
				}
				const { data: { success }, status } = await axios.post(`${this.protocolMethod}://${this.domain}/api/v1/user/password/change`, initialData, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})

				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/user/password/change, received ${status}`)
				} else {
					this.passSuccess = success
				}
				console.log('editNewData', this.editNewData, this.$store.getters.user.detail.password_reset)

				if(this.$v.password_repeat.sameAsPassword && this.checkPassValidate && this.$v.password.minLength && this.passSuccess && this.checkLatinPass) {
					this.editDataHundler()
					this.checkEditData(this.editNewData, this)
					// this.currentStep++

					if(!this.$store.getters.user.detail.onboarding_edit) {
						this.currentStep++
					} else if(this.$store.getters.user.detail.onboarding_edit && this.$store.getters.user.detail.password_reset) {
						this.dialog = false
					} else {
						return
					}
				}
			},
			checkEditData(data, context) {
				console.log('checkEditData', data, context.checkCompleteEdit)
				const editDataNull = Object.keys(data).find(item => data[item] === null || data[item] == false)
				console.log(editDataNull)
				if(editDataNull == undefined) {
					console.log('undefined')
					context.checkCompleteEdit = false
				} else {
					console.log('not undefined')
					context.checkCompleteEdit = true
				}
			},
			showPassword({ target }) {
				if( target.dataset.type == 'password' ) {
					if(this.typePass === 'password') {
						this.typePass = 'text'
						this.showPass = true
					} else {
						this.typePass = 'password'
						this.showPass = false
					}
				}

				if( target.dataset.type == 'password_repeat' ) {
					if(this.typePassRepeat === 'password') {
						this.typePassRepeat = 'text'
						this.showPassRepeat = true
					} else {
						this.typePassRepeat = 'password'
						this.showPassRepeat = false
					}
				}
			},
			checkNumAndWord() {
				if( this.password.match(/[a-zA-Z]/g) && this.password.match(/[0-9]/g) ) {
					this.checkPassValidate = true
				} else {
					this.checkPassValidate = false
				}
				if ( !this.password.match(/[\u0400-\u04FF]/gi) ) {
					this.checkLatinPass = true
				} else {
					this.checkLatinPass = false
				}
			},
			checkNumAndWordUser() {
				if( this.userData.password.match(/[a-zA-Z]/g) && this.userData.password.match(/[0-9]/g) ) {
					this.checkPassValidate = true
				} else {
					this.checkPassValidate = false
				}
				if ( !this.userData.password.match(/[\u0400-\u04FF]/gi) ) {
					this.checkLatinPass = true
				} else {
					this.checkLatinPass = false
				}
			},
			prev() {
				if( this.currentStep > 0  ) {
					this.historyItems.splice(-1, 1)
					this.dataTarrif.steps.splice(-1, 1)
					this.selectedTarrif.tariffId = null
					this.currentStep--
                }
			},
			triggerAnimate() {
				if( this.currentStep > 0 && this.currentStep <= 4 ) {
					this.welcomeWinItems = false
					setTimeout(() => this.welcomeWinItems = true, 200)
				}
			},
			changeField({ target }, step) {
				if(target.checked && target.type === 'checkbox') {
					this.checkedItems[`${step}Step`] = {...this.checkedItems[`${step}Step`], ...{[target.id]: target.value}}
				}

				if(!target.checked && target.type === 'checkbox') {
					delete this.checkedItems[`${step}Step`][target.id]
					this.checkedItems[`${step}Step`] = {...this.checkedItems[`${step}Step`]}
				}

				if(target.checked && target.type === 'radio') {
					this.checkedItems[`${step}Step`] = {...{[target.id]: target.value}}
				}

			},
			selectTarrif(e, id, title, cost) {
				this.selectedTarrif.tariffId = id
				this.selectedTarrif.tarrifTitle = title
				this.selectedTarrif.tarrifCost = cost
			},
			getRecomendedTarrif(data) {
				return data.find(item => item.isRecommend)
			},
			scrollToLastItem() {
				const elem = document.querySelector('.welcomeWinLive .welcomeWinLive__item:last-child')
				if(elem) elem.scrollIntoView({block: "center", behavior: "smooth"})
			},
			setFields(e) {
				const context = this
				console.log('setFields')
				this.editData = {...this.editData, [e.target.name]: e.target.value}
				this.editNewData = {...this.editNewData, [e.target.name]: e.target.value}
				context.checkEditData(this.editNewData, this)
				if(this.editNewData.last_name.length && this.editNewData.first_name.length && this.editNewData.middle_name.length) {
					this.disabledSelect = false
				} else {
					this.disabledSelect = true
				}
			},
			setEmailUser(e) {
				const emailRegexpPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
				// let obj = {
				// 	id: JSON.parse(localStorage.getItem('user')).id,
				// 	validate: {
				// 		email: this.userData.email
				// 	}
				// }

				if(e.target.value.length > 0) {
					this.editEmailValid = true
					this.editEmailInvalid = false
				}
				if(e.target.value.length < 1) {
					this.editEmailInvalid = true
					this.editEmailValid = false
				}
				if(emailRegexpPattern.test(e.target.value)) {
					this.editEmailValid = true
					this.editEmailInvalid = false
				} else {
					this.editEmailInvalid = true
					// this.editEmailValid = false
				}

				// this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
				// 	console.log('data phone', data.data)
				// 	if(data.data.message) {
				// 		this.editEmailValid = false
				// 		this.editEmailInvalid = true
				// 	} else {
				// 		this.editEmailInvalid = false
				// 		this.editEmailValid = true
				// 	}
				// })
			},
			setEmail(e) {
				// const emailRegexpPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
				this.editData = {...this.editData, [e.target.name]: e.target.value}
				this.editNewData = {...this.editNewData, [e.target.name]: e.target.value}
				// let obj = {
				// 	id: JSON.parse(localStorage.getItem('user')).id,
				// 	is_step: true,
				// 	validate: {
				// 		email: this.editNewData.email
				// 	}
				// }
				// if(e.target.value.length > 0) {
				// 	this.editEmailValid = true
				// 	this.editEmailInvalid = false
				// 	this.checkEditData(this.editNewData, this)
				// }
				// if(e.target.value.length < 1) {
				// 	this.editEmailInvalid = true
				// 	this.editEmailValid = false
				// }
				// if(emailRegexpPattern.test(e.target.value)) {
				// 	this.editEmailValid = true
				// 	this.editEmailInvalid = false
				// 	this.checkEditData(this.editNewData, this)
				// } else {
				// 	this.editEmailInvalid = true
				// 	this.editEmailValid = false
				// }

				// this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
				// 	console.log('data phone', data.data)
				// 	if(data.data.message) {
				// 		this.editEmailValid = false
				// 		this.editEmailInvalid = true
				// 		this.checkCompleteEdit = true
				// 	} else {
				// 		this.editEmailInvalid = false
				// 		this.checkCompleteEdit = false
				// 	}
				// })
			},
			setNameCompany(e) {
				this.editData = {...this.editData, [e.target.name]: e.target.value}
				this.editNewData = {...this.editNewData, [e.target.name]: e.target.value}
				let obj = {
					id: JSON.parse(localStorage.getItem('user')).id,
					validate: {
						company_name: `${this.editNewData.company_name}.gateway.${MAIN_DOMAIN}`
					}
				}
				if(e.target.value.length > 0) {
					this.editCompanyValid = true
					this.editCompanyInvalid = false
					this.checkEditData(this.editNewData, this)
					this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
						console.log('data phone', data.data)
						if(data.data.message) {
							this.editCompanyValid = false
							this.editCompanyInvalid = true
						} else {
							this.editCompanyValid = true
							this.editCompanyInvalid = false
						}
					})
				} else if(e.target.value.length < 1) {
					this.editCompanyInvalid = true
					this.editCompanyValid = false
				} else {
					return false
				}
			},
			checkPhoneValidateUser() {
				let transformPhone = this.userData.phone ? this.userData.phone.replace(' ', '').replace(/[{()}]/g, '') : ""

				let obj = {
					id: JSON.parse(localStorage.getItem('user')).id,
					is_step: true,
					validate: {
						phone: transformPhone
					}
				}

				this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
					console.log('data phone', data.data)
					if(data.data.message) {
						this.phoneValid = false
						this.phoneInvalid = true
					} else {
						this.phoneValid = true
						this.phoneInvalid = false
					}
				})
			},
			checkPhoneValidate: debounce(function(value) {
				// let transformPhone = this.editData.phone ? this.editData.phone.replace(/\s/g, '').replace(/[{()}]/g, '') : ""
				// let phoneRegexpPattern = /((\+38\s)?\(?\d{3}\)?[\s]?(\d{7}|\d{3}[\s]\d{2}[\s]\d{2}|\d{3}-\d{4}))/g
				let obj = {
					id: JSON.parse(localStorage.getItem('user')).id,
					is_step: true,
					validate: {
						phone: value
					}
				}
				if(value) {
					this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
						console.log('data phone', data)
						if(data.data.message) {
							this.phoneValid = false
							// this.phoneInvalid = true
							this.checkCompleteEdit = true
						} else {
							this.phoneValid = true
							// this.phoneInvalid = false
							this.checkCompleteEdit = false
						}
						// if(e.target.value.length > 0) {
						// 	this.phoneValid = true
						// 	this.phoneInvalid = false
						// } 
						// if(e.target.value.length < 1) {
						// 	this.phoneInvalid = true
						// 	this.phoneValid = false
						// }
						// if(phoneRegexpPattern.test(e.target.value)) {
						// 	this.phoneValid = true
						// 	this.phoneInvalid = false
						// } else {
						// 	this.phoneInvalid = true
						// 	this.phoneValid = false
						// }
					}).catch(e => {
						console.log('erorrrr', e)
						this.phoneValid = false
						// this.phoneInvalid = true
					})
				} else {
					// this.phoneInvalid = false
					this.phoneValid = true
				}
			}, 450),
			setPhone(e) {
				// const phoneRegexpPattern = /^\+[0-9]{2}\s\((\d+)\)-\d{3}-\d{2}-\d{2}/g
				// const phoneRegexpPattern = /^\+[0-9]{12}/g
				console.log('WORK PHONE!!!', e.target.value.length)
				const phoneRegexpPattern = /((\+38\s)?\(?\d{3}\)?[\s]?(\d{7}|\d{3}[\s]\d{2}[\s]\d{2}|\d{3}-\d{4}))/g
				this.editData = {...this.editData, [e.target.name]: e.target.value}
				this.editNewData = {...this.editNewData, [e.target.name]: e.target.value}
				// let transformPhone = this.editData.phone ? this.editData.phone.replace(' ', '').replace(/[{()}]/g, '') : ""
				// let obj = {
				// 	id: JSON.parse(localStorage.getItem('user')).id,
				// 	is_step: true,
				// 	validate: {
				// 		phone: transformPhone
				// 	}
				// }
				// if(e.target.value.length > 0) {
				// 	this.phoneValid = true
				// 	this.phoneInvalid = false
				// 	this.checkEditData(this.editNewData, this)
				// }
				// if(e.target.value.length < 1) {
				// 	this.phoneInvalid = true
				// 	this.phoneValid = false
				// 	this.checkEditData(this.editNewData, this)
				// }
				if(phoneRegexpPattern.test(e.target.value)) {
					this.phoneValid = true
					this.phoneInvalid = false
					console.log('check rege phone')
					// this.checkEditData(this.editNewData, this)
				} else {
					this.phoneInvalid = true
					this.phoneValid = false
					// this.checkEditData(this.editNewData, this)
				}

				// if(e.target.value.length) {
				// 	this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
				// 		console.log('data phone', data)
				// 		if(data.data.message) {
				// 			this.phoneValid = false
				// 			this.phoneInvalid = true
				// 			this.checkCompleteEdit = true
				// 		} else {
				// 			this.phoneValid = true
				// 			this.phoneInvalid = false
				// 			this.checkCompleteEdit = false
				// 		}
				// 		if(e.target.value.length > 0) {
				// 			this.phoneValid = true
				// 			this.phoneInvalid = false
				// 			this.checkEditData(this.editNewData, this)
				// 		}
				// 		if(e.target.value.length < 1) {
				// 			this.phoneInvalid = true
				// 			this.phoneValid = false
				// 			this.checkEditData(this.editNewData, this)
				// 		}
				// 		if(phoneRegexpPattern.test(e.target.value)) {
				// 			this.phoneValid = true
				// 			this.phoneInvalid = false
				// 			this.checkEditData(this.editNewData, this)
				// 		} else {
				// 			this.phoneInvalid = true
				// 			this.phoneValid = false
				// 			this.checkEditData(this.editNewData, this)
				// 		}
				// 	}).catch(e => {
				// 		console.log('erorrrr', e)
				// 		this.phoneValid = false
				// 		this.phoneInvalid = true
				// 	})
				// }
				// console.log('transform phone', this.editData.phone.replace(' ', '').replace(/[{()}]/g, ''))
			},
			// setPhone(e) {
			// 	// const phoneRegexpPattern = /^\+[0-9]{2}\s\((\d+)\)-\d{3}-\d{2}-\d{2}/g
			// 	// const phoneRegexpPattern = /^\+[0-9]{12}/g
			// 	console.log('WORK PHONE!!!', e.target.value.length)
			// 	const phoneRegexpPattern = /((\+38\s)?\(?\d{3}\)?[\s]?(\d{7}|\d{3}[\s]\d{2}[\s]\d{2}|\d{3}-\d{4}))/g
			// 	this.editData = {...this.editData, [e.target.name]: e.target.value}
			// 	this.editNewData = {...this.editNewData, [e.target.name]: e.target.value}
			// 	let transformPhone = this.editData.phone ? this.editData.phone.replace(' ', '').replace(/[{()}]/g, '') : ""
			// 	let obj = {
			// 		id: JSON.parse(localStorage.getItem('user')).id,
			// 		is_step: true,
			// 		validate: {
			// 			phone: transformPhone
			// 		}
			// 	}
			// 	// if(e.target.value.length > 0) {
			// 	// 	this.phoneValid = true
			// 	// 	this.phoneInvalid = false
			// 	// 	this.checkEditData(this.editNewData, this)
			// 	// }
			// 	// if(e.target.value.length < 1) {
			// 	// 	this.phoneInvalid = true
			// 	// 	this.phoneValid = false
			// 	// 	this.checkEditData(this.editNewData, this)
			// 	// }
			// 	// if(phoneRegexpPattern.test(e.target.value)) {
			// 	// 	this.phoneValid = true
			// 	// 	this.phoneInvalid = false
			// 	// 	this.checkEditData(this.editNewData, this)
			// 	// } else {
			// 	// 	this.phoneInvalid = true
			// 	// 	this.phoneValid = false
			// 	// 	this.checkEditData(this.editNewData, this)
			// 	// }

			// 	if(e.target.value.length) {
			// 		this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
			// 			console.log('data phone', data)
			// 			if(data.data.message) {
			// 				this.phoneValid = false
			// 				this.phoneInvalid = true
			// 				this.checkCompleteEdit = true
			// 			} else {
			// 				this.phoneValid = true
			// 				this.phoneInvalid = false
			// 				this.checkCompleteEdit = false
			// 			}
			// 			if(e.target.value.length > 0) {
			// 				this.phoneValid = true
			// 				this.phoneInvalid = false
			// 				this.checkEditData(this.editNewData, this)
			// 			}
			// 			if(e.target.value.length < 1) {
			// 				this.phoneInvalid = true
			// 				this.phoneValid = false
			// 				this.checkEditData(this.editNewData, this)
			// 			}
			// 			if(phoneRegexpPattern.test(e.target.value)) {
			// 				this.phoneValid = true
			// 				this.phoneInvalid = false
			// 				this.checkEditData(this.editNewData, this)
			// 			} else {
			// 				this.phoneInvalid = true
			// 				this.phoneValid = false
			// 				this.checkEditData(this.editNewData, this)
			// 			}
			// 		}).catch(e => {
			// 			console.log('erorrrr', e)
			// 			this.phoneValid = false
			// 			this.phoneInvalid = true
			// 		})
			// 	}
			// 	// console.log('transform phone', this.editData.phone.replace(' ', '').replace(/[{()}]/g, ''))
			// },
			blurDomainName(e) {
				let companyName = localStorage.getItem('company_name') !== null ? localStorage.getItem('company_name') : ''
				let obj = {
					id: JSON.parse(localStorage.getItem('user')).id,
					validate: {
						domain: this.editData.domain
					}
				}
				this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
					if(data.data.message && e.target.value.length < 1) {
						this.editDomainValid = true
						this.editDomainInvalid = false
						this.editData = {...this.editData, [e.target.name]: `${companyName}.gateway.${this.editData.main_domain}`}
						this.editNewData = {...this.editNewData, [e.target.name]: `${companyName}.gateway.${this.editData.main_domain}`}
					} else if(!data.data.message && e.target.value.length < 1) {
						this.editDomainValid = true
						this.editDomainInvalid = false
						this.editData = {...this.editData, [e.target.name]: `${companyName}.gateway.${this.editData.main_domain}`}
						this.editNewData = {...this.editNewData, [e.target.name]: `${companyName}.gateway.${this.editData.main_domain}`}
					} else {
						return
					}
				})
			},
			setDomainName(e) {
				let currentValue = e.target.value
				let maskDomain = /[^0-9a-z-]/g
				if(currentValue.match(maskDomain) !== null) {
					console.log('work field')
					currentValue = currentValue.replace(maskDomain, '')
					this.editData = {...this.editData, [e.target.name]: `${currentValue}.gateway.${this.editData.main_domain}`}
					this.editNewData = {...this.editNewData, [e.target.name]: `${currentValue}.gateway.${this.editData.main_domain}`}
				} else {
					console.log('not work field')
					this.editData = {...this.editData, [e.target.name]: `${currentValue}.gateway.${this.editData.main_domain}`}
					this.editNewData = {...this.editNewData, [e.target.name]: `${currentValue}.gateway.${this.editData.main_domain}`}
					let obj = {
						id: JSON.parse(localStorage.getItem('user')).id,
						validate: {
							domain: this.editData.domain
						}
					}

					this.$store.dispatch('stepsAsyncValidation', obj).then(data => {
						console.log('data domain', data.data)
						if(data.data.message) {
							this.editDomainValid = false
							this.editDomainInvalid = true
							console.log('1')
						} else if(data.data.message && e.target.value.length < 1) {
							this.editDomainValid = true
							this.editDomainInvalid = false
							console.log('2')
						} else if(!data.data.message && e.target.value.length > 0) {
							this.editDomainValid = true
							this.editDomainInvalid = false
							console.log('3')
						} else if(!data.data.message && e.target.value.length < 1) {
							this.editDomainValid = true
							this.editDomainInvalid = false
							console.log('4')
						} else if(e.target.value.length > 0) {
							this.editDomainValid = true
							this.editDomainInvalid = false
							this.checkEditData(this.editNewData, this)
							console.log('5')
						} else if(e.target.value.length < 1) {
							this.editDomainInvalid = true
							this.editDomainValid = false
							console.log('6')
						} else {
							return
						}
					})
				}
			},
			async editSubmitForm(e) {
				e.stopPropagation()
				e.preventDefault()
				const token = this.$store.getters.token
				let self = this
				if(!this.$v.editNewData.$invalid && !this.phoneInvalid && !this.editEmailInvalid) {
					console.log('work')
					this.disabledCompleteButton = true
					const checkGenerate = await axios.get(`${this.protocolMethod}://${this.domain}/api/v1/user/company/check_generate`, {
						headers: { 
							'Authorization': `Bearer ${token}`
						}
					})
					console.log('checkGenerate', checkGenerate.data.success)

					if(checkGenerate.data.success) {
						self.completeUser()
						console.log('completeUser')
					} else {
						console.log('echo', this.$echo)
						self.initState = false
						new this.$echo().onListen('.company.create').then(response => { 
							console.log('response response', response)
							self.completeUser()
						})
					}
				} else {
					console.log('not work')
				}

			},
			async getPositions() {
				const token = this.$store.getters.token
				const { data: { data }, status } = await axios.get(`${this.protocolMethod}://${this.domain}/api/v1/directories/core/positions/list`, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})

				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/directories/core/positions/list, received ${status}`)
				} else {
					console.log('positions data', data)
					const defPos = data.filter(item => item.directory_id === 1)
					console.log('defPos', defPos)
					this.defaultPosition = defPos
					this.positions = data
				}
			},
			editDataHundler() {
				this.getPositions()
				let {
					company,
					email,
					first_name,
					last_name,
					phone
				} = this.$store.getters.user

				let transformPhone = ''

				console.log('company', company)
				if(phone !== null) {
					// transformPhone = phone.slice(3)
					// transformPhone = phone.slice(1)
					transformPhone = phone
					this.isPhone = true
				} else {
					transformPhone = ""
				}

				if(email !== null) {
					this.isEmail = true
				}

				this.editData = {
					company_name: company.name,
					main_domain: company.main_domain,
					domain: `${company.domain.split('.')[0]}.gateway.${company.main_domain}`,
					siteName: company.domain,
					email,
					last_name,
					first_name,
					middle_name: null,
					phone,
				}
				this.editNewData = {
					company_name: company.name,
					domain: `${company.domain.split('.')[0]}.gateway.${company.main_domain}`,
					email,
					last_name,
					first_name,
					middle_name: null,
					phone: transformPhone,
					position_id: null
				}

				localStorage.setItem('company_name', company.domain.split('.')[0])
				console.log('phones', this.editNewData, transformPhone)
			},
			changeSelect(value) {
				console.log('changeSelect', value.directory_id)
				this.editSelect = true
				this.editNewData.position_id = value.directory_id
				this.checkEditData(this.editNewData, this)
			},
			async completeUsers() {
				const token = this.$store.getters.token
				const { first_name, last_name, middle_name, email, phone, password } = this.userData
				const userDataTransform = { first_name, last_name, middle_name, email, phone: phone.replace(/[{()}]/g, '').replace(/\s/g, '') }
				const userDataPassword = {
					data: {
						password
					}
				}

				console.log(userDataTransform, userDataPassword, `${this.protocolMethod}://${this.domain}/api/v1/user/auth`)

				this.disabledCompleteButton = true

				const { data: { data:user } } = await axios.post(`${this.protocolMethod}://${this.domain}/api/v1/user/auth`, { data: userDataTransform }, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				const userPasswordResponse = await axios.post(`${this.protocolMethod}://${this.domain}/api/v1/user/password/change`, userDataPassword, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})


				console.log(user, userPasswordResponse)

				// if(!userDataResponse.data.status || !userPasswordResponse.data.status) {
				// 	throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/user/auth, received ${status}`)
				// } else {
				// 	console.log('user', userDataResponse.data.data.user)
				// 	localStorage.clear()
				// 	window.location.href = `https://${userDataResponse.data.data.user.company.user_main_domain}/?user_id=${userDataResponse.data.data.user.id}`
				// 	this.dialog = false
				// }

				console.log('user', user)
				// localStorage.clear()
				await this.$store.dispatch('changeUserData', user)
				this.disabledCompleteButton = false
				this.dialog = false
				// window.location.href = `https://${userDataResponse.data.data.company.user_main_domain}/?user_id=${userDataResponse.data.data.id}`
			},
			async completeUser() {
				const token = this.$store.getters.token
				let phone = this.editNewData.phone.replace(/[{()}]/g, '').replace(/\s/g, '')
				let transformData

				if(this.isEmail) {
					transformData = {
						...this.editNewData,
						phone,
						auth_email: this.editNewData.email
					}
				} else if(this.isPhone) {
					transformData = {
						...this.editNewData,
						phone,
						auth_phone: phone
					}
				} else if(!this.isPhone && !this.isEmail) {
					transformData = {
						...this.editNewData,
						phone,
						auth_phone: true,
						auth_email: true
					}
				} else {
					transformData = {
						...this.editNewData,
						phone: phone.replace(/\s/g, '')
					}
				}
				console.log('editNewData', transformData)
				this.disabledCompleteButton = true
				const { data: { data:user }, status } = await axios.post(`${this.protocol}//${this.domain}/api/v1/user/auth`, { data: transformData }, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/user/auth, received ${status}`)
				} else {
					const oldDomain = JSON.parse(localStorage.getItem('domain'))
					const newDomain = this.editNewData.domain
					console.log(user)
					if(oldDomain !== newDomain) { // oldData !== Newdata
						localStorage.clear()
						this.disabledCompleteButton = false
						window.location.href = `${PROTOCOL}://${user.company.user_main_domain}/?user_id=${user.id}`
						this.dialog = false
					} else {
						localStorage.getItem('company_name') && localStorage.removeItem('company_name')
						this.disabledCompleteButton = false
						this.dialog = false
						setTimeout(() => {
							this.$store.dispatch('changeUserData', user)
						}, 1000)
					}
				}
			},
			async getTarrifs() {
				const token = this.$store.getters.token
				const { data, status } = await axios.post(`${this.protocol}//${this.domain}/api/v1/tariffs/calculationTariffs`, this.dataTarrif, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/tariffs/calculationTariffs, received ${status}`)
				} else {
					this.responseTarrifItems = data
					this.recommendTarrif= this.getRecomendedTarrif(this.responseTarrifItems.data).title
				}
			},
			async paymentTrial() {
				const token = this.$store.getters.token
				const tariff_id = this.selectedTarrif.tariffId
				const initialData = {
					data: {
						tariff_id,
						provider: 'trial'
					}
				}
				const { data: { success }, status } = await axios.post(`${this.protocol}//${this.domain}/api/v1/tariffs/subscription/pay/`, initialData, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				console.log(success)
				if(!status) {
					throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/tariffs/subscription/pay/, received ${status}`)
				} else {
					// this.payment.status = success ? success : false
					this.currentStep += 1
				}
			},
			paymentProvider() {
				const token = this.$store.getters.token
				const tariff_id = this.selectedTarrif.tariffId
				const provider = this.payment.methods.liqpay
				const initialData = {
					data: {
						tariff_id,
						provider
					}
				}
				const result = axios.post(`${this.protocol}//${this.domain}/api/v1/tariffs/subscription/pay/`, initialData, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				}).then(({ statusText }) => statusText)

				return result

				// console.log(data)

				// if(!status) {
				// 	throw new Error(`Could not fetch ${this.protocolMethod}://${this.domain}/api/v1/tariffs/calculationTariffs, received ${status}`)
				// } else {
				// 	console.log(data)
				// 	this.payment.provider = success ? success : false
				// }
			},
			generateLiqpayData(publicKey, privateKey, amount) {
				console.log(amount)
				const block = document.createElement('div')
				block.innerHTML = new Liqpay(publicKey, privateKey).cnb_form({
					'action': 'pay',
					'amount': amount,
					'currency': 'UAH',
					'description': 'Tests tests',
					'version': '3'
				})
				this.liqPaySettings = {...this.liqPaySettings, data: block.querySelector("form input[name=data]").value, signature: block.querySelector("form input[name=signature]").value}
			},
			liqpayPayment() {
				let self = this
				console.log(window.LiqPayCheckout)
				window.LiqPayCheckout.init({
					data: this.liqPaySettings.data,
					signature: this.liqPaySettings.signature,
					// embedTo: "#liqpay_checkout",
					language: "ru",
					mode: "popup", // embed || popupб
				}).on("liqpay.callback", async function(data) {
					console.log(data.status, data)
					if(data.status === 'success') {
						console.log('success!', self.payment)
						self.payment.status = true
					}
				}).on("liqpay.ready", function(data){
					// ready
					console.log('Ready', data)
				}).on("liqpay.close", function(data){
					// close
					if(self.payment.status) {
						self.paymentProvider().then(data =>  {
							if( data === 'OK' ) {
								self.payment.checkStatus = true
								console.log('OK!', self.payment, self)
							}
						})
					}
					console.log('data', data)
				})
			}
		},
		computed: {
			checkedLength() { return this.checkedItems },
			checkPaymentChanges() { return this.payment.status },
			stepData() { return this.steps.data },
			computedStep() { return this.currentStep },
			positionsList() { return this.positions },
			...mapGetters([
				'protocol',
			])
		},
		updated() {
			if(this.currentStep < 5) {
				this.scrollToLastItem()
			}
			// if(this.currentStep === 8) {
			// 	console.log('positions', this.positions[0])
			// 	this.editNewData['position_id'] = this.positions[0].id
			// 	return
			// }
		},
		created() {
			// if(!this.$store.getters.user.detail.is_owner) this.initState = true
			// if(this.$store.getters.user.detail.onboarding_edit && this.$store.getters.user.detail.is_owner && this.$store.getters.user.detail.password_reset) this.initState = true
		},
		async mounted() {
			const token = this.$store.getters.token
			const { data, status } = await axios.get(`${this.protocol}//${this.domain}/api/v1/tariffs/steps`, {
				headers: {
					'Authorization': `Bearer ${token}`
				}
			})
			if(!status) {
				throw new Error(`Could not fetch ${this.protocol}//${this.domain}/api/v1/tariffs/steps, received ${status}`)
			} else {
				this.steps.data = data
				this.triggerAnimate()
				console.log('is owner', this.$store.getters.user.detail.is_owner)
				if(!this.$store.getters.user.detail.onboarding_edit && this.$store.getters.user.detail.password_reset) this.currentStep = 1
				if(this.$store.getters.user.detail.onboarding_edit && this.$store.getters.user.detail.password_reset) this.currentStep = 7
				if(!this.$store.getters.user.detail.onboarding_edit && this.$store.getters.user.detail.onboarding_tariff) this.currentStep = 7

				if(!this.$store.getters.user.detail.is_owner) {
					this.currentStep = 9
					console.log('user user user user', localStorage.getItem('user'))
					this.userData = {
						...this.userData,
						first_name: localStorage.getItem('user') !== null ? JSON.parse(localStorage.getItem('user')).first_name : null,
						last_name: localStorage.getItem('user') !== null ? JSON.parse(localStorage.getItem('user')).last_name : null,
						middle_name: localStorage.getItem('user') !== null ? JSON.parse(localStorage.getItem('user')).middle_name : null,
					}
				}
			}
			
			// await new this.$echo().onListen('.company.create').then(response => { 
			// 	console.log('response response', response)
			// 	this.completeUser()
			// })

			// console.log(this.$echo())

			// if(this.$store.getters.user.detail.is_owner) setTimeout(() => this.initState = true, 85000)
		}
	}
</script>

<style lang="sass" scoped>
	.fade-enter-active, .fade-leave-active
		transition: opacity .35s ease-in-out
	.fade-enter, .fade-leave-to
		opacity: 0
	.test
		display: none
		background: rgba(0, 0, 0, .5)
		position: absolute
		width: 25%
		top: 9.3%
		left: 4.2%
		bottom: 0
		height: 100vh
		z-index: 2
		color: #fff
		overflow-y: auto
		// pointer-events: none
</style>
